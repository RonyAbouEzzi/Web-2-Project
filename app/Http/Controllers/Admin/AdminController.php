<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Municipality, Office, ServiceRequest, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard(Request $request)
    {
        $validated = $request->validate([
            'period' => 'nullable|in:all,this_month,last_30,last_90,this_year',
            'municipality_id' => 'nullable|integer|exists:municipalities,id',
            'office_id' => 'nullable|integer|exists:offices,id',
        ]);

        $period = $validated['period'] ?? 'this_month';
        $municipalityId = isset($validated['municipality_id']) ? (int) $validated['municipality_id'] : null;
        $officeId = isset($validated['office_id']) ? (int) $validated['office_id'] : null;

        if ($municipalityId && $officeId) {
            $officeBelongsToMunicipality = Office::whereKey($officeId)
                ->where('municipality_id', $municipalityId)
                ->exists();

            if (!$officeBelongsToMunicipality) {
                $officeId = null;
            }
        }

        [$currentStart, $currentEnd, $previousStart, $previousEnd, $periodLabel] = $this->resolveDashboardPeriod($period);

        $municipalities = Municipality::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $officeOptionsQuery = Office::query()
            ->with('municipality:id,name')
            ->orderBy('name');

        if ($municipalityId) {
            $officeOptionsQuery->where('municipality_id', $municipalityId);
        }

        $officeOptions = $officeOptionsQuery->get(['id', 'name', 'municipality_id']);

        // Requests and revenue (filter-aware)
        $currentRequestsQuery = ServiceRequest::query();
        $this->applyRequestDashboardScope($currentRequestsQuery, $currentStart, $currentEnd, $municipalityId, $officeId);

        $previousRequestsQuery = ServiceRequest::query();
        $this->applyRequestDashboardScope($previousRequestsQuery, $previousStart, $previousEnd, $municipalityId, $officeId);

        $totalRequests = (clone $currentRequestsQuery)->count();
        $previousTotalRequests = (clone $previousRequestsQuery)->count();

        $pendingRequests = (clone $currentRequestsQuery)
            ->where('status', 'pending')
            ->count();

        $totalRevenue = (clone $currentRequestsQuery)
            ->where('payment_status', 'paid')
            ->sum('amount_paid');

        $previousTotalRevenue = (clone $previousRequestsQuery)
            ->where('payment_status', 'paid')
            ->sum('amount_paid');

        // Platform totals with scope hints
        $currentUsersQuery = User::query()->where('role', 'citizen');
        if ($currentEnd) {
            $currentUsersQuery->where('created_at', '<=', $currentEnd);
        }
        $totalUsers = $currentUsersQuery->count();

        $previousUsersQuery = User::query()->where('role', 'citizen');
        if ($previousEnd) {
            $previousUsersQuery->where('created_at', '<=', $previousEnd);
        }
        $previousTotalUsers = $previousUsersQuery->count();

        $currentOfficesQuery = Office::query();
        if ($municipalityId) {
            $currentOfficesQuery->where('municipality_id', $municipalityId);
        }
        if ($officeId) {
            $currentOfficesQuery->whereKey($officeId);
        }
        if ($currentEnd) {
            $currentOfficesQuery->where('created_at', '<=', $currentEnd);
        }
        $totalOffices = $currentOfficesQuery->count();

        $previousOfficesQuery = Office::query();
        if ($municipalityId) {
            $previousOfficesQuery->where('municipality_id', $municipalityId);
        }
        if ($officeId) {
            $previousOfficesQuery->whereKey($officeId);
        }
        if ($previousEnd) {
            $previousOfficesQuery->where('created_at', '<=', $previousEnd);
        }
        $previousTotalOffices = $previousOfficesQuery->count();

        $stats = [
            'total_users' => $totalUsers,
            'total_offices' => $totalOffices,
            'total_requests' => $totalRequests,
            'pending_requests' => $pendingRequests,
            'total_revenue' => $totalRevenue,
        ];

        $trends = [
            'total_users' => $this->buildTrend($totalUsers, $previousTotalUsers),
            'total_offices' => $this->buildTrend($totalOffices, $previousTotalOffices),
            'total_requests' => $this->buildTrend($totalRequests, $previousTotalRequests),
            'total_revenue' => $this->buildTrend($totalRevenue, $previousTotalRevenue),
        ];

        // Recent requests (filter-aware)
        $recentRequestsQuery = ServiceRequest::with(['citizen', 'service', 'office'])
            ->latest()
            ->limit(10);

        $this->applyRequestDashboardScope($recentRequestsQuery, $currentStart, $currentEnd, $municipalityId, $officeId);
        $recentRequests = $recentRequestsQuery->get();

        // Top offices (filter-aware)
        $officeStatsQuery = Office::query()
            ->with('municipality:id,name');

        if ($municipalityId) {
            $officeStatsQuery->where('municipality_id', $municipalityId);
        }
        if ($officeId) {
            $officeStatsQuery->whereKey($officeId);
        }

        $officeStats = $officeStatsQuery
            ->withCount([
                'requests as requests_count' => function (Builder $query) use ($currentStart, $currentEnd): void {
                    if ($currentStart && $currentEnd) {
                        $query->whereBetween('created_at', [$currentStart, $currentEnd]);
                    }
                },
            ])
            ->having('requests_count', '>', 0)
            ->orderByDesc('requests_count')
            ->limit(5)
            ->get();

        // Monthly chart data (year grid + current filter scope)
        $monthlyRawQuery = ServiceRequest::query()
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month_num, COUNT(*) as total')
            ->whereYear('created_at', now()->year);

        $this->applyRequestDashboardScope($monthlyRawQuery, $currentStart, $currentEnd, $municipalityId, $officeId);

        $monthlyRaw = $monthlyRawQuery
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->pluck('total', 'month_num')
            ->mapWithKeys(fn ($total, $month) => [(int) $month => (int) $total]);

        $chartLabels = [];
        $chartValues = [];

        for ($month = 1; $month <= 12; $month++) {
            $chartLabels[] = Carbon::create()->month($month)->format('M');
            $chartValues[] = (int) ($monthlyRaw[$month] ?? 0);
        }

        $dashboardFilters = [
            'period' => $period,
            'period_label' => $periodLabel,
            'municipality_id' => $municipalityId,
            'office_id' => $officeId,
            'is_scoped' => (bool) ($municipalityId || $officeId || $period !== 'this_month'),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'trends',
            'recentRequests',
            'officeStats',
            'municipalities',
            'officeOptions',
            'dashboardFilters',
            'chartLabels',
            'chartValues'
        ));
    }

    private function resolveDashboardPeriod(string $period): array
    {
        $now = Carbon::now();

        return match ($period) {
            'all' => [null, null, null, null, 'All time'],
            'last_30' => [
                $now->copy()->subDays(29)->startOfDay(),
                $now->copy()->endOfDay(),
                $now->copy()->subDays(59)->startOfDay(),
                $now->copy()->subDays(30)->endOfDay(),
                'Last 30 days',
            ],
            'last_90' => [
                $now->copy()->subDays(89)->startOfDay(),
                $now->copy()->endOfDay(),
                $now->copy()->subDays(179)->startOfDay(),
                $now->copy()->subDays(90)->endOfDay(),
                'Last 90 days',
            ],
            'this_year' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfDay(),
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfYear(),
                'This year',
            ],
            default => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfDay(),
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
                'This month',
            ],
        };
    }

    private function applyRequestDashboardScope(
        Builder $query,
        ?Carbon $start,
        ?Carbon $end,
        ?int $municipalityId,
        ?int $officeId
    ): void {
        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($officeId) {
            $query->where('office_id', $officeId);
            return;
        }

        if ($municipalityId) {
            $query->whereHas('office', function (Builder $officeQuery) use ($municipalityId): void {
                $officeQuery->where('municipality_id', $municipalityId);
            });
        }
    }

    private function buildTrend(float|int $current, float|int $previous): array
    {
        $currentValue = (float) $current;
        $previousValue = (float) $previous;

        if ($previousValue === 0.0) {
            if ($currentValue === 0.0) {
                return ['text' => '0%', 'direction' => 'flat'];
            }

            return ['text' => 'New', 'direction' => 'up'];
        }

        $delta = (($currentValue - $previousValue) / abs($previousValue)) * 100;
        $roundedDelta = round($delta, 1);

        if ($roundedDelta === 0.0) {
            return ['text' => '0%', 'direction' => 'flat'];
        }

        $formatted = rtrim(rtrim(number_format(abs($roundedDelta), 1, '.', ''), '0'), '.');

        return [
            'text' => ($roundedDelta > 0 ? '+' : '-') . $formatted . '%',
            'direction' => $roundedDelta > 0 ? 'up' : 'down',
        ];
    }

    // Municipality Management
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

    // Office Management
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

    // User Management
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

    // Reporting
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

        $monthlyRequests = ServiceRequest::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')->pluck('total', 'month')
            ->mapWithKeys(fn ($total, $month) => [(int) $month => (int) $total]);

        return view('admin.reports', compact(
            'requestsByOffice', 'revenueByOffice', 'requestsByStatus', 'monthlyRequests'
        ));
    }
}
