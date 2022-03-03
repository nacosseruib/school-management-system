<!DOCTYPE html>
<html lang="en">
<head>
	<title>@yield('pageHeaderTitle')</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="A powerful school application for all your school activities, Software for school result, generate PIN, check result student report card online with PIN.">
    <meta name="keywords" content="School HR, School E-Portal, School Result Application, Check result, parent, staff, teacher, principal, School Result, Result, Result different template, Report card, Report sheet, Generate PIN for result">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ asset('appAssets/login/images/icons/favicon.ico') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/vendor/animate/animate.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('appAssets/login/css/main.css') }}">
<!--===============================================================================================-->
@yield('styles')
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url({{asset('appAssets/login/images/bg-01.jpg')}});">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            
            @yield('content')

            </div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('appAssets/login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/vendor/select2/select2.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/vendor/daterangepicker/moment.min.js') }}"></script>
	<script src="{{ asset('appAssets/login/vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/vendor/countdowntime/countdowntime.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('appAssets/login/js/main.js"></script>
@yield('scripts')
</body>
</html>