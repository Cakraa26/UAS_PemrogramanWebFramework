@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
  <div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-6 mx-4">
        <div class="card p-sm-7 p-2">
          <div class="app-brand justify-content-center mt-5">
            <a href="{{ url('/') }}" class="app-brand-link gap-3">
              <span class="app-brand-logo demo">@include('_partials.macros')</span>
              <span class="app-brand-text demo text-heading fw-semibold">{{ config('variables.templateName') }}</span>
            </a>
          </div>
          <div class="card-body mt-1">
            <h4 class="mb-1">Welcome to {{ config('variables.templateName') }}! 👋🏻</h4>
            <p class="mb-5">Please sign-in to your account and start the adventure</p>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form id="formAuthentication" class="mb-5" action="{{ route('login.process') }}" method="POST">
              @csrf
              <div class="form-floating form-floating-outline mb-5 form-control-validation">
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                  autofocus />
                <label for="email">Email</label>
                @error('email')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-5">
                <div class="form-password-toggle form-control-validation">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="password" id="password" class="form-control" name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                      <label for="password">Password</label>
                      @error('password')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                    <span class="input-group-text cursor-pointer"><i
                        class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                  </div>
                </div>
              </div>
              <div class="mb-5">
                <button class="btn btn-primary d-grid w-100" type="submit">login</button>
              </div>
            </form>

            <p class="text-center mb-5">
              <span>New on our platform?</span>
              <a href="{{ url('auth/register-basic') }}">
                <span>Create an account</span>
              </a>
            </p>
          </div>
        </div>
        <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}"
          class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
      </div>
    </div>
  </div>
@endsection
