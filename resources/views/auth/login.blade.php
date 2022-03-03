@extends('layouts.loginLayout') 
@section('pageHeaderTitle') {{ __('Login to access your account') }} @endsection
@section('content')
        <form method="POST" action="{{ route('login') }}">
                    @csrf
					<span class="login100-form-title p-b-49">
						<h6><a href="{{ url('/')}}"><img src="{{ asset('appAssets/login/images/icons/favicon.ico') }}" /></a></h6>
					   <h3 style="color:purple;">{{ __('SCHOOL E-PORTAL') }}</h3>
					   <hr /> 
					   <h4 class="text-success">{{ __('Login Now') }}</h4>
					</span>
					@include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
					<div class="wrap-input100 m-b-23" data-validate = "{{ __('E-Mail is reauired') }}">
						<span class="label-input100">{{ __('E-Mail Address') }}</span>
						<input required class="input100 {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}"  autofocus type="email" placeholder="{{ __('Type your Email') }}">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
					</div>

					<div class="wrap-input100" data-validate="{{ __('Password is required') }}">
                        <span class="label-input100">{{ __('Password') }}</span>
                        <input required id="password" type="password" class="input100 {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  placeholder="{{ __('Type your password') }}">
                        <span class="focus-input100" data-symbol="&#xf190;"></span>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
					</div>
					
					<div class="text-right p-t-8 p-b-31">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
					</div>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button type="submit" name="submitLogin" class="login100-form-btn">
                            {{ __('Login') }}
							</button>
						</div>
					</div>
					<!--<div class="flex-c-m">
						<a href="#" class="login100-social-item bg1">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="login100-social-item bg2">
							<i class="fa fa-twitter"></i>
						</a>

						<a href="#" class="login100-social-item bg3">
							<i class="fa fa-google"></i>
						</a>
					</div>-->
		</form>
    @endsection