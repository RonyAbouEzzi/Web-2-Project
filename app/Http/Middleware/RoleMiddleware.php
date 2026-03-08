<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role-based access control middleware.
 *
 * Security guarantees:
 *  1. Unauthenticated requests → redirect to login (stores intended URL)
 *  2. Deactivated accounts → logout immediately
 *  3. Wrong-role requests → redirect to correct dashboard (no 403 leaking info)
 *  4. URI manipulation (e.g. citizen trying /admin/*) → silently redirected
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // ── 1. Must be authenticated ─────────────────────────────
        if (!Auth::check()) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login')
                ->with('info', 'Please sign in to continue.');
        }

        $user = Auth::user();

        // ── 2. Account must be active ─────────────────────────────
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
        }

        // ── 3. Must have the correct role ─────────────────────────
        if (!in_array($user->role, $roles)) {
            // Silently redirect to own dashboard — no information disclosure
            return redirect($this->dashboardFor($user))
                ->with('error', 'You do not have access to that area.');
        }

        return $next($request);
    }

    private function dashboardFor($user): string
    {
        return match ($user->role) {
            'admin'       => route('admin.dashboard'),
            'office_user' => route('office.dashboard'),
            default       => route('citizen.dashboard'),
        };
    }
}
