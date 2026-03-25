<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCitizenProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->isCitizen() || $user->hasCompletedCitizenProfile()) {
            return $next($request);
        }

        $missingFields = $user->missingCitizenProfileFields();
        $message = 'Please complete your profile before submitting service requests.';

        if (!empty($missingFields)) {
            $message .= ' Missing: ' . implode(', ', $missingFields) . '.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'missing_fields' => $missingFields,
            ], 422);
        }

        return redirect()
            ->route('citizen.profile')
            ->with('error', $message);
    }
}
