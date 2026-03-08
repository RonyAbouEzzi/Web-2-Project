<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Password};
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    // ── Registration ─────────────────────────────────────────────
    public function showRegister()
    {
        // Already logged-in users go straight to their dashboard
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'national_id' => 'required|string|max:20',
            'phone'    => 'nullable|string|max:20',
        ];

        if ($request->filled('first_name') || $request->filled('last_name')) {
            $rules['first_name'] = 'required|string|max:50';
            $rules['last_name']  = 'required|string|max:50';
        } else {
            $rules['name'] = 'required|string|max:100';
        }

        $docField = $request->hasFile('national_id_document') ? 'national_id_document' : 'national_id_doc';
        $rules[$docField] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';

        $data = $request->validate($rules);

        $docPath = null;
        if ($request->hasFile($docField)) {
            $docPath = $request->file($docField)->store('id_documents', 'private');
        }

        $fullName = isset($data['first_name'])
            ? trim($data['first_name'] . ' ' . $data['last_name'])
            : $data['name'];

        $user = User::create([
            'name'        => $fullName,
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'national_id' => $data['national_id'],
            'phone'       => $data['phone'] ?? null,
            'id_document' => $docPath,   // ← correct column name
            'role'        => 'citizen',
            'is_active'   => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('citizen.dashboard')
                         ->with('success', 'Welcome to E-Services!');
    }

    // ── Login ─────────────────────────────────────────────────────
    public function showLogin()
    {
        // Already logged-in users go straight to their dashboard
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been deactivated. Contact support.']);
        }

        // 2FA check
        if ($user->two_factor_secret && $user->two_factor_enabled) {
            session(['2fa_user_id' => $user->id]);
            Auth::logout();
            return redirect()->route('2fa.verify');
        }

        $request->session()->regenerate();
        return $this->redirectByRole($user);
    }

    // ── 2FA ───────────────────────────────────────────────────────
    public function show2FA()
    {
        if (!session('2fa_user_id')) return redirect()->route('login');
        return view('auth.2fa');
    }

    public function verify2FA(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $user = User::findOrFail(session('2fa_user_id'));

        $g2fa = new Google2FA();
        if (!$g2fa->verifyKey($user->two_factor_secret, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid code. Please try again.']);
        }

        session()->forget('2fa_user_id');
        Auth::login($user);
        $request->session()->regenerate();
        return $this->redirectByRole($user);
    }

    // ── Social OAuth ──────────────────────────────────────────────
    public function redirectToProvider(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'facebook']), 404);
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Social login failed. Please try again.']);
        }

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'            => $socialUser->getName(),
                'password'        => Hash::make(Str::random(24)),
                'role'            => 'citizen',
                'social_provider' => $provider,
                'social_id'       => $socialUser->getId(),
                'avatar'          => $socialUser->getAvatar(),
                'is_active'       => true,
            ]
        );

        if (!$user->is_active) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        Auth::login($user);
        return $this->redirectByRole($user);
    }

    // ── Password Reset ────────────────────────────────────────────
    public function showForgotPassword()
    {
        if (Auth::check()) return $this->redirectByRole(Auth::user());
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Reset link sent! Check your inbox.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')
                ->with('success', 'Password reset successfully. Please sign in.')
            : back()->withErrors(['email' => __($status)]);
    }

    // ── Logout ────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'You have been signed out.');
    }

    // ── Role redirect helper ──────────────────────────────────────
    private function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        return match ($user->role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'office_user' => redirect()->route('office.dashboard'),
            default       => redirect()->route('citizen.dashboard'),
        };
    }
}
