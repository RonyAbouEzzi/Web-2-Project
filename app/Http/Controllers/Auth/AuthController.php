<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Password};
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use PragmaRX\Google2FA\Google2FA;
use Throwable;

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
        // Accept both "otp" and legacy "otp_code" from the 2FA form.
        $request->merge([
            'otp' => $request->input('otp', $request->input('otp_code')),
        ]);

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
        abort_unless(in_array($provider, ['google', 'github']), 404);

        try {
            $driver = Socialite::driver($provider);

            if ($provider === 'github') {
                // Request e-mail scope to improve success rate for GitHub sign-in.
                $driver = $driver->scopes(['read:user', 'user:email']);
            }

            return $driver->redirect();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors(['email' => 'Unable to start social login. Please check OAuth configuration.']);
        }
    }

    public function handleProviderCallback(string $provider)
    {
        try {
            abort_unless(in_array($provider, ['google', 'github']), 404);

            $driver = Socialite::driver($provider);
            if ($provider === 'github') {
                $driver = $driver->scopes(['read:user', 'user:email']);
            }

            $socialUser = $driver->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Social login failed. Please try again.']);
        }

        $email = $socialUser->getEmail();
        if (!$email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your social account has no accessible email address.']);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            if (!$user->is_active) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been deactivated.']);
            }

            $user->forceFill([
                'social_provider' => $provider,
                'social_id'       => $socialUser->getId(),
                'avatar'          => $socialUser->getAvatar() ?: $user->avatar,
            ]);

            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }

            $user->save();

            if (blank($user->password)) {
                session([
                    'social_password_setup' => [
                        'mode'     => 'link-existing',
                        'user_id'  => $user->id,
                        'provider' => $provider,
                        'email'    => $user->email,
                        'name'     => $user->name,
                    ],
                ]);

                return redirect()->route('social.password.form')
                    ->with('info', 'Set a password to complete your account setup.');
            }

            Auth::login($user);
            session()->regenerate();
            return $this->redirectByRole($user);
        }

        session([
            'social_password_setup' => [
                'mode'      => 'create-new',
                'provider'  => $provider,
                'social_id' => $socialUser->getId(),
                'email'     => $email,
                'name'      => $socialUser->getName() ?: 'Social User',
                'avatar'    => $socialUser->getAvatar(),
            ],
        ]);

        return redirect()->route('social.password.form')
            ->with('info', 'Choose a password to finish creating your account.');
    }

    public function showSocialPasswordForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        $payload = session('social_password_setup');
        if (!$payload) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your social signup session expired. Please try again.']);
        }

        return view('auth.social-password', [
            'social' => $payload,
        ]);
    }

    public function storeSocialPassword(Request $request)
    {
        $payload = session('social_password_setup');
        if (!$payload) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your social signup session expired. Please try again.']);
        }

        $data = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        if (($payload['mode'] ?? null) === 'link-existing') {
            $user = User::find($payload['user_id'] ?? 0);

            if (!$user) {
                session()->forget('social_password_setup');
                return redirect()->route('login')
                    ->withErrors(['email' => 'Account was not found. Please sign in again.']);
            }

            $user->password = Hash::make($data['password']);
            $user->save();
        } else {
            $user = User::create([
                'name'            => $payload['name'] ?? 'Social User',
                'email'           => $payload['email'],
                'password'        => Hash::make($data['password']),
                'role'            => 'citizen',
                'social_provider' => $payload['provider'] ?? null,
                'social_id'       => $payload['social_id'] ?? null,
                'avatar'          => $payload['avatar'] ?? null,
                'is_active'       => true,
                'email_verified_at' => now(),
            ]);
        }

        session()->forget('social_password_setup');

        if (!$user->is_active) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        Auth::login($user);
        $request->session()->regenerate();
        return $this->redirectByRole($user)
            ->with('success', 'Password saved successfully.');
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

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'email' => 'Unable to send reset email. Check SMTP settings and try again.',
            ]);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
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
