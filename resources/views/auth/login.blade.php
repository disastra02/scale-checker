@extends('layouts.app')

@push('css')
<style>
    main {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;

    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="login-loading">
        <div class="card h-100 border-0">
            <div class="card-body">
                <div class="text-center">
                    <h1 class=""><i class="fa-solid fa-spinner fa-spin-pulse"></i></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="login-mobile" style="display: none;">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6 col-sm-12">
                <div class="card border-0">
                    <div class="card-body p-2">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/login.png') }}" width="65%" alt="warehouse">
                        </div>
    
                        <h1 class="mb-3 fw-bold">Login</h1>
    
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="emailField" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
    
                                <div class="col-md-6">
                                    <input id="emailField" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-4">
                                <label for="passwordField" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="passwordField" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            {{-- <div class="row mb-4">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
    
                            <div class="row mb-0">
                                <div class="col-md-12">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-dark btn-block">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
    
                                    {{-- @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="login-desktop" style="display: none;">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4 col-sm-8">
                <div class="card shadow-lg border-light-subtle">
                    <div class="card-body py-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/login.png') }}" width="55%" alt="warehouse">
                        </div>
    
                        <div class="mb-3">
                            <h3 class="mb-0 fw-bold">Login</h3>
                            <span class="">Selamat Datang Kembali</span>
                        </div>
    
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="email" class="col-md-12 col-form-label text-md-start">{{ __('Email') }}</label>
    
                                <div class="col-md-12">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-4">
                                <label for="password" class="col-md-12 col-form-label text-md-start">{{ __('Password') }}</label>
    
                                <div class="col-md-12">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            {{-- <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
    
                            <div class="row mb-0">
                                <div class="col-md-12">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-dark btn-block">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
    
                                    {{-- @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(() => {
                $('.login-loading').hide();
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    $('.login-mobile').show();
                    $('.login-desktop').remove();
                } else {
                    $('.login-desktop').show();
                    $('.login-mobile').remove();
                }

            }, 2000);
        });
    </script>
@endpush
