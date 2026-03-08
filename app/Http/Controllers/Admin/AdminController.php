<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Municipality, Office, ServiceRequest, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_users'       => User::where('role', 'citizen')->count(),
            'total_offices'     => Office::count(),
            'total_requests'    => ServiceRequest::count(),
            'pending_requests'  => ServiceRequest::where('status', 'pending')->count(),
            'total_revenue'     => ServiceRequest::where('payment_status', 'paid')->sum('amount_paid'),
            'requests_this_month' => ServiceRequest::whereMonth('created_at', now()->month)->count(),
        ];

        $recentRequests = ServiceRequest::with(['citizen', 'service', 'office'])
            ->latest()->limit(10)->get();

        $officeStats = Office::withCount('requests')
            ->orderBy('requests_count', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentRequests', 'officeStats'));
    }

    // ── Municipality Management ───────────────────────────────────
    public function municipalities()
    {
        $municipalities = Municipality::withCount('offices')->paginate(15);
        return view('admin.municipalities.index', compact('municipalities'));
    }

    public function storeMunicipality(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'region'  => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);
        Municipality::create($data);
        return back()->with('success', 'Municipality created successfully.');
    }

    public function updateMunicipality(Request $request, Municipality $municipality)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'region'    => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $municipality->update($data);
        return back()->with('success', 'Municipality updated.');
    }

    public function destroyMunicipality(Municipality $municipality)
    {
        $municipality->delete();
        return back()->with('success', 'Municipality deleted.');
    }

    // ── Office Management ─────────────────────────────────────────
    public function offices()
    {
        $offices = Office::with('municipality')->paginate(15);
        return view('admin.offices.index', compact('offices'));
    }

    public function storeOffice(Request $request)
    {
        $data = $request->validate([
            'municipality_id' => 'required|exists:municipalities,id',
            'name'            => 'required|string|max:255',
            'address'         => 'required|string',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('office_logos', 'public');
        }

        Office::create($data);
        return back()->with('success', 'Office created.');
    }

    public function updateOffice(Request $request, Office $office)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'address'         => 'required|string',
            'municipality_id' => 'required|exists:municipalities,id',
            'is_active'       => 'boolean',
        ]);
        $office->update($data);
        return back()->with('success', 'Office updated.');
    }

    public function destroyOffice(Office $office)
    {
        $office->delete();
        return back()->with('success', 'Office deleted.');
    }

    // ── User Management ───────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->role) $query->where('role', $request->role);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        $users = $query->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createOfficeUser(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'office_id' => 'required|exists:offices,id',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'office_user',
        ]);

        $user->offices()->attach($data['office_id'], ['role' => 'staff']);

        return back()->with('success', 'Office user created.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account {$status}.");
    }

    // ── Reporting ─────────────────────────────────────────────────
    public function reports()
    {
        $requestsByOffice = Office::withCount('requests')
            ->orderBy('requests_count', 'desc')->get();

        $revenueByOffice = Office::withSum(
            ['requests as revenue' => fn ($q) => $q->where('payment_status', 'paid')],
            'amount_paid'
        )->get();

        $requestsByStatus = ServiceRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $monthlyRequests = ServiceRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')->pluck('total', 'month');

        return view('admin.reports', compact(
            'requestsByOffice', 'revenueByOffice', 'requestsByStatus', 'monthlyRequests'
        ));
    }
}
