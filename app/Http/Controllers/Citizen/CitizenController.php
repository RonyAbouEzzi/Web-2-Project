<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Events\{AppointmentReminderBroadcast, NewRequestSubmitted, RequestDocumentUploaded};
use App\Models\{Appointment, Feedback, Message, Office, Service, ServiceRequest};
use App\Services\{QrCodeService, PaymentService, PdfService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class CitizenController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────
    public function dashboard()
    {
        $user     = Auth::user();
        $requests = $user->serviceRequests()->with(['service', 'office'])->latest()->paginate(10);
        $upcomingAppointments = Appointment::where('citizen_id', $user->id)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->with('office')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(3)
            ->get();
        return view('citizen.dashboard', compact('requests', 'upcomingAppointments'));
    }

    // ── Profile ───────────────────────────────────────────────────
    public function profile()
    {
        $user = Auth::user();
        $requests = $user->serviceRequests()->with(['service', 'office'])->latest()->take(5)->get();
        $paidRequests = $user->serviceRequests()
            ->where('payment_status', 'paid')
            ->with(['service', 'office'])
            ->latest()
            ->take(10)
            ->get();
        return view('citizen.profile', compact('user', 'requests', 'paidRequests'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'password'     => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $data['name'];
        $user->phone = $data['phone'] ?? $user->phone;
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // ── Browse Services ───────────────────────────────────────────
    public function browseOffices(Request $request)
    {
        $municipalities = \App\Models\Municipality::where('is_active', true)->orderBy('name')->get();
        $query = Office::where('is_active', true)->with('municipality');

        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($mid = $request->municipality_id) {
            $query->where('municipality_id', $mid);
        }

        $offices = $query->withCount('requests')->paginate(12);
        return view('citizen.offices.index', compact('offices', 'municipalities'));
    }

    public function showOffice(Office $office)
    {
        $office->load(['services.category', 'feedbacks' => fn ($q) => $q->latest()->limit(5)]);
        return view('citizen.offices.show', compact('office'));
    }

    // ── Service Request ───────────────────────────────────────────
    public function showService(Service $service)
    {
        $service->load('office');

        $paymentService = app(PaymentService::class);
        $baseCurrency = $service->currency ?? 'USD';
        $basePrice = $service->price;

        $convertedPrices = [];
        foreach (['USD', 'LBP', 'EUR'] as $currency) {
            if ($currency !== $baseCurrency) {
                $convertedPrices[$currency] = $paymentService->convertCurrency($basePrice, $baseCurrency, $currency);
            }
        }

        return view('citizen.services.show', compact('service', 'convertedPrices'));
    }

    public function submitRequest(Request $request, Service $service)
    {
        $request->validate([
            'notes'         => 'nullable|string|max:1000',
            'documents'     => 'required|array',
            'documents.*'   => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $serviceRequest = ServiceRequest::create([
            'reference_number' => ServiceRequest::generateReference(),
            'citizen_id'       => Auth::id(),
            'service_id'       => $service->id,
            'office_id'        => $service->office_id,
            'notes'            => $request->notes,
            'amount_paid'      => $service->price,
        ]);

        foreach ($request->file('documents') as $file) {
            $path = $file->store('request_documents/' . $serviceRequest->id, 'private');
            $document = $serviceRequest->documents()->create([
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_by'   => 'citizen',
            ]);

            event(new RequestDocumentUploaded($serviceRequest, $document));
        }

        $qrPath = app(QrCodeService::class)->generate($serviceRequest);
        $serviceRequest->update(['qr_code' => $qrPath]);
        event(new NewRequestSubmitted($serviceRequest));

        return redirect()->route('citizen.payment', $serviceRequest)
                         ->with('success', 'Request submitted. Please complete payment.');
    }

    // ── Payment ───────────────────────────────────────────────────
    public function showPayment(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);
        $stripeKey = config('services.stripe.key');
        return view('citizen.payment', compact('serviceRequest', 'stripeKey'));
    }

    public function processPayment(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);

        $data = $request->validate(['payment_method' => 'required|in:card,crypto']);

        $paymentService = app(PaymentService::class);
        $result = $paymentService->process($serviceRequest, $data['payment_method'], $request->all());

        if (!$result['success']) {
            return back()->withErrors(['payment' => $result['message']]);
        }

        // Stripe card payment: redirect to Stripe Checkout
        if ($data['payment_method'] === 'card' && isset($result['redirect_url'])) {
            return redirect($result['redirect_url']);
        }

        // Crypto payment: show wallet address for manual transfer
        if ($data['payment_method'] === 'crypto' && !empty($result['requires_confirmation'])) {
            return view('citizen.payment-crypto', [
                'serviceRequest'  => $serviceRequest,
                'wallet_address'  => $result['wallet_address'],
                'crypto_amount'   => $result['crypto_amount'],
                'crypto_currency' => $result['crypto_currency'],
                'transaction_id'  => $result['transaction_id'],
            ]);
        }

        // Direct success (fallback)
        $serviceRequest->update([
            'payment_status' => 'paid',
            'payment_method' => $data['payment_method'],
            'transaction_id' => $result['transaction_id'],
        ]);

        return redirect()->route('citizen.requests.show', $serviceRequest)
                         ->with('success', 'Payment successful! Your request is now being processed.');
    }

    public function paymentSuccess(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('citizen.payment', $serviceRequest)
                             ->withErrors(['payment' => 'Invalid payment session.']);
        }

        $result = app(PaymentService::class)->verifyStripeSession($sessionId);

        if ($result['success']) {
            $serviceRequest->update([
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'transaction_id' => $result['transaction_id'],
            ]);

            return redirect()->route('citizen.requests.show', $serviceRequest)
                             ->with('success', 'Payment successful! Your request is now being processed.');
        }

        return redirect()->route('citizen.payment', $serviceRequest)
                         ->withErrors(['payment' => $result['message']]);
    }

    public function paymentCancel(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);

        return redirect()->route('citizen.payment', $serviceRequest)
                         ->with('warning', 'Payment was cancelled. You can try again.');
    }

    public function confirmCryptoPayment(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);

        $data = $request->validate([
            'tx_hash' => 'required|string|min:10|max:100',
        ]);

        $result = app(PaymentService::class)->confirmCrypto($serviceRequest, $data['tx_hash']);

        if ($result['success']) {
            $serviceRequest->update([
                'payment_status' => 'paid',
                'payment_method' => 'crypto',
                'transaction_id' => $result['transaction_id'],
            ]);

            return redirect()->route('citizen.requests.show', $serviceRequest)
                             ->with('success', 'Crypto payment confirmed! Your request is now being processed.');
        }

        return back()->withErrors(['payment' => $result['message']]);
    }

    // ── My Requests ───────────────────────────────────────────────
    public function myRequests(Request $request)
    {
        $query = Auth::user()->serviceRequests()->with(['service', 'office'])->latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(15);
        return view('citizen.requests.index', compact('requests'));
    }

    public function showRequest(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);
        $serviceRequest->load(['service', 'office', 'documents', 'statusLogs.changedBy', 'messages.sender', 'appointment']);
        return view('citizen.requests.show', compact('serviceRequest'));
    }

    public function trackByQr(string $reference)
    {
        $req = ServiceRequest::where('reference_number', $reference)
                             ->with(['service', 'office', 'statusLogs'])->firstOrFail();
        return view('citizen.requests.track', compact('req'));
    }

    // ── Receipt Download ──────────────────────────────────────────
    public function downloadReceipt(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);
        abort_unless($serviceRequest->payment_status === 'paid', 403);

        $path = app(PdfService::class)->generateReceipt($serviceRequest);
        return app(PdfService::class)->stream($path, "receipt-{$serviceRequest->reference_number}.pdf");
    }

    // ── Appointments ──────────────────────────────────────────────
    public function bookAppointment(Request $request)
    {
        $data = $request->validate([
            'office_id'          => 'required|exists:offices,id',
            'service_request_id' => 'nullable|exists:service_requests,id',
            'appointment_date'   => 'required|date|after:today',
            'appointment_time'   => 'required|date_format:H:i',
            'notes'              => 'nullable|string',
        ]);

        $appointment = Appointment::create(array_merge($data, ['citizen_id' => Auth::id()]));
        event(new AppointmentReminderBroadcast($appointment, 'appointment_booked'));

        return back()->with('success', 'Appointment booked successfully.');
    }

    // ── Feedback ──────────────────────────────────────────────────
    public function submitFeedback(Request $request)
    {
        $data = $request->validate([
            'office_id'          => 'required|exists:offices,id',
            'service_request_id' => 'nullable|exists:service_requests,id',
            'rating'             => 'required|integer|min:1|max:5',
            'comment'            => 'nullable|string|max:1000',
        ]);

        Feedback::create(array_merge($data, ['citizen_id' => Auth::id()]));
        return back()->with('success', 'Thank you for your feedback!');
    }

    // ── Messages ──────────────────────────────────────────────────
    public function sendMessage(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);
        $data = $request->validate(['body' => 'required|string|max:2000']);

        $msg = $serviceRequest->messages()->create([
            'sender_id' => Auth::id(),
            'body'      => $data['body'],
        ]);

        return response()->json(['message' => $msg->load('sender'), 'success' => true]);
    }

    // ── Documents ─────────────────────────────────────────────────
    public function downloadDocument(ServiceRequest $serviceRequest, string $docId)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);
        $doc = $serviceRequest->documents()->findOrFail($docId);
        return response()->download(
            storage_path('app/private/' . $doc->file_path),
            $doc->original_name
        );
    }
}
