@extends('layouts/blankLayout')

@section('title', 'Sign In')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <h4 class="mb-1">Welcome back! 👋</h4>
                    <p class="mb-6">Sign in to your account to access municipal e-services</p>

                    @if($errors->any())
                    <div class="alert alert-danger py-2 mb-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif

                    <form id="formAuthentication" class="mb-6" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" autofocus required />
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="d-flex justify-content-between">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                                <a href="{{ route('password.request') }}">
                                    <span>Forgot Password?</span>
                                </a>
                            </div>
                        </div>
                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign In</button>
                        </div>
                    </form>

                    @if(Route::has('social.redirect'))
                    <div class="divider my-6">
                        <div class="divider-text">or sign in with</div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                            <img src="https://www.google.com/favicon.ico" alt="Google" width="16" height="16">
                            Google
                        </a>
                        <a href="{{ route('social.redirect', 'github') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                            <i class="icon-base bx bxl-github"></i>
                            GitHub
                        </a>
                    </div>
                    @endif

                    <p class="text-center mt-6">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}">
                            <span>Create one free</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


