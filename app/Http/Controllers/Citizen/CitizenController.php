<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\{Appointment, Feedback, Message, Office, Service, ServiceRequest};
use App\Services\{QrCodeService, PaymentService, PdfService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};

class CitizenController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────
    public function dashboard()
    {
        $user     = Auth::user();
        $requests = $user->serviceRequests()->with(['service', 'office'])->latest()->paginate(10);
        return view('citizen.dashboard', compact('requests'));
    }

    // ── Profile ───────────────────────────────────────────────────
    public function profile()
    {
        $user = Auth::user();
        $requests = $user->serviceRequests()->with(['service', 'office'])->latest()->take(5)->get();
        return view('citizen.profile', compact('user', 'requests'));
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
        return view('citizen.services.show', compact('service'));
    }

    public function submitRequest(Request $request, Service $service)
    {
        $request->validate([
            'notes'         => 'nullable|string|max:1000',
            'documents'     => 'required|array|min:1',
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
            $serviceRequest->documents()->create([
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_by'   => 'citizen',
            ]);
        }

        $qrPath = app(QrCodeService::class)->generate($serviceRequest);
        $serviceRequest->update(['qr_code' => $qrPath]);

        return redirect()->route('citizen.payment', $serviceRequest)
                         ->with('success', 'Request submitted. Please complete payment.');
    }

    // ── Payment ───────────────────────────────────────────────────
    public function showPayment(ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);
        return view('citizen.payment', compact('serviceRequest'));
    }

    public function processPayment(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->citizen_id === Auth::id(), 403);

        $data = $request->validate(['payment_method' => 'required|in:card,crypto']);

        $result = app(PaymentService::class)->process($serviceRequest, $data['payment_method'], $request->all());

        if ($result['success']) {
            $serviceRequest->update([
                'payment_status' => 'paid',
                'payment_method' => $data['payment_method'],
                'transaction_id' => $result['transaction_id'],
            ]);
            return redirect()->route('citizen.requests.show', $serviceRequest)
                            ->with('success', 'Payment successful! Your request is now being processed.');
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

        Appointment::create(array_merge($data, ['citizen_id' => Auth::id()]));
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

        if (!empty($data['service_request_id'])) {
            $alreadyExists = Feedback::where('citizen_id', Auth::id())
                ->where('service_request_id', $data['service_request_id'])
                ->exists();

            if ($alreadyExists) {
                return back()->withErrors([
                    'feedback' => 'You have already submitted feedback for this request.'
                ])->withInput();
            }
        }

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
