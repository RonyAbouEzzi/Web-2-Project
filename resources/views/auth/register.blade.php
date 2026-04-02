@extends('layouts/blankLayout')

@section('title', 'Create Account')

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
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <h4 class="mb-1">Create your account 🚀</h4>
                    <p class="mb-6">Register to access municipal e-services online</p>

                    @if($errors->any())
                    <div class="alert alert-danger py-2 mb-4">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif

                    @if(Route::has('social.redirect'))
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 flex-grow-1 justify-content-center">
                            <img src="https://www.google.com/favicon.ico" alt="Google" width="16" height="16">
                            Google
                        </a>
                        <a href="{{ route('social.redirect', 'github') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 flex-grow-1 justify-content-center">
                            <i class="icon-base bx bxl-github"></i>
                            GitHub
                        </a>
                    </div>
                    <div class="divider my-6">
                        <div class="divider-text">or register with email</div>
                    </div>
                    @endif

                    <form id="formAuthentication" class="mb-6" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="John" required />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Doe" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+961 7x xxx xxx" />
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="national_id_number" class="form-label">National ID Number</label>
                            <input type="text" class="form-control" id="national_id_number" name="national_id_number" value="{{ old('national_id_number') }}" placeholder="LB-XXXXXXXXX" required />
                            <div class="form-text">Used for identity verification. Your number is encrypted.</div>
                        </div>
                        <div class="mb-6">
                            <label for="national_id_document" class="form-label">National ID Document <small class="text-muted">(photo or scan)</small></label>
                            <input type="file" class="form-control" id="national_id_document" name="national_id_document" accept=".jpg,.jpeg,.png,.pdf" />
                            <div class="form-text">JPG, PNG or PDF - Max 5MB</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                    <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                    <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="my-7">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required />
                                <label class="form-check-label" for="terms-conditions">
                                    I agree to the <a href="javascript:void(0);">Terms of Service</a> and <a href="javascript:void(0);">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary d-grid w-100" type="submit">Create Account</button>
                    </form>

                    <p class="text-center">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}">
                            <span>Sign in instead</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
