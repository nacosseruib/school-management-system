@extends('layouts.loginLayout')
@section('pageHeaderTitle') {{ __('RESET PASSWORD::WELCOME TO SCHOOL RESULT COMPUTATION') }}  @endsection
@section('content')
        <form method="POST" action="{{ route('password.email') }}">
                    @csrf
					<span class="login100-form-title p-b-49">
                    <h3 class="text-success">{{ __('SCHOOL RESULT') }} &nbsp; {{ __('E-PORTAL') }}</h3> <hr /> <h4 class="text-secondary">{{ __('Reset Password') }}</h4>
					</span>

					<div class="wrap-input100 m-b-23" data-validate = "{{ __('E-Mail is reauired') }}">
						<span class="label-input100">{{ __('E-Mail Address') }}</span>
						<input class="input100 {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" required autofocus type="email" placeholder="{{ __('Type your Email') }}">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
					</div>

                    <div class="text-right p-t-8 p-b-31">
                        @if (Route::has('login'))
                            <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('Go Back To Login') }}
                            </a>
                        @endif
					</div>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" name="submitLogin" class="login100-form-btn">
                            {{ __('Send Password Reset Link') }}
							</button>
						</div>
                    </div>
		</form>
    @endsection