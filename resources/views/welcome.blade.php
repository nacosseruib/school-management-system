<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie ie6" lang="en"><![endif]-->
<!--[if IE 7]><html class="ie ie7" lang="en"><![endif]-->
<!--[if IE 8]><html class="ie ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if (gte IE 10)|!(IE)]><!--><html lang="en"><!--<![endif]-->

<head>
    
    <!-- Your Basic Site Informations -->
    <title>Welcome To Your School E-Portal</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Ajax">
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="A powerful school application for all your school activities, Software for school result, generate PIN, check result student report card online with PIN.">
    <meta name="keywords" content="School HR, School E-Portal, School Result Application, Check result, parent, staff, teacher, principal, School Result, Result, Result different template, Report card, Report sheet, Generate PIN for result">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/style.css') }}">
    
    <!-- Custom Colors -->
    <!--<link rel="stylesheet" href="css/colors/blue/color.css">-->
    <!--<link rel="stylesheet" href="css/colors/green/color.css">-->
    <!--<link rel="stylesheet" href="css/colors/pink/color.css">
    <link rel="stylesheet" href="{{ asset('welcomeAssets/css/colors/purple/color.css') }}">-->
    <!--<link rel="stylesheet" href="css/colors/yellow/color.css">-->
    
    <!--[if lt IE 9]>
    	<script src="js/html5.js"></script>
        <script src="js/respond.min.js"></script>
	<![endif]-->
    
    <!--[if lt IE 8]>
    	<link rel="stylesheet" href="css/ie-older.css">
    <![endif]
    <noscript><link rel="stylesheet" href="{{ asset('welcomeAssets/css/no-js.css') }}"></noscript>-->
    <!-- Favicons -->
	<link rel="icon" type="image/png" href="{{ asset('appAssets/login/images/icons/favicon.ico') }}"/>
	<link rel="apple-touch-icon" href="{{ asset('appAssets/login/images/icons/favicon.ico') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('appAssets/login/images/icons/favicon.ico') }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('appAssets/login/images/icons/favicon.ico') }}">
</head>
<body>
    
    <!-- #body-wrap -->
    <div id="body-wrap">
        
        <!-- #header -->
        <header id="header" data-parallax="scroll" data-speed="0.2" data-natural-width="1920" data-natural-height="1080" data-image-src="{{ asset('welcomeAssets/images/content/bg/1.jpg') }}">
            <!-- .header-overlay -->
            <div class="header-overlay">
                <!-- #navigation -->
                <nav id="navigation" class="navbar scrollspy">
                    <!-- .container -->
                    <div class="container">
                        <div class="navbar-brand">
                            <!--<a href="{{url('/')}}"><img src=" " alt=" "></a>-->
                        </div>
                        <ul class="nav navbar-nav">

                            <li>
                                <a href="{{route('login')}}" class="smooth-scroll">Teacher's Login</a>
                            </li>
                            
                        </ul>
                    </div>
                    <!-- .container end -->
                </nav>
                <!-- #navigation end -->
                
                <!-- .header-content -->
                <div class="header-content">
                    <!-- .container -->
                    <div class="container">
                         <!-- .row -->
                         <div class="row" style="margin-top:-70px;">

                                <div class="col-sm-5 col-md-4">
                                    <div class="header-form">
                                        <div class="header-form-heading">
                                            <p class="text-center"> <i class="fa fa-key"></i> 
                                                Enter student Reg. No. and PIN
                                            </p>
                                        </div>
                                        <div class="header-form-body">
                                            <div class="submit-status"></div>
                                            
                                            <form method="post" action="{{route('postViewStudentReport')}}">
                                            @csrf
                                                <input type="text" required name="studentRegistrationId" value="{{ old('studentRegistrationId') }}" placeholder="Student Reg. ID." />
                                                @if ($errors->has('studentRegistrationId'))
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $errors->first('studentRegistrationId') }}</strong>
                                                    </span>
                                                @endif

                                                <input type="password" required name="pinCode" placeholder="PIN Code" style="width:100%" />
                                                @if ($errors->has('pinCode'))
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $errors->first('pinCode') }}</strong>
                                                    </span>
                                                @endif

                                                <br /><br />

                                                <select name="schoolSession" required placeholder="Select School Session" style="width:100%" >
                                                    <option value="">Select School Session</option>
                                                    @if(isset($getPublishedSession))
                                                    @forelse($getPublishedSession as $listPublicSession)
                                                        <option value="{{ $listPublicSession->publicSessionID }}" {{ ($listPublicSession->publicSessionID == old('schoolSession')) ? 'Selected' : '' }}>{{ $listPublicSession->session_name .' - '. $listPublicSession->school_term }}</option>
                                                    @empty
                                                    @endforelse
                                                    @endif
                                                </select>
                                                @if ($errors->has('schoolSession'))
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $errors->first('schoolSession') }}</strong>
                                                    </span>
                                                @endif
                                                
                                                <br /><br />

                                                <select name="schoolTerm" required placeholder="Select School Term" style="width:100%">
                                                    <option value="">Select Term</option>
                                                    @if(isset($allTerm))
                                                    @forelse($allTerm as $listTerm)
                                                        <option value="{{ $listTerm->termID }}" {{ ($listTerm->termID == old('schoolTerm')) ? 'Selected' : '' }}>{{ $listTerm->term_name }}</option>
                                                    @empty
                                                    @endforelse
                                                    @endif
                                                </select>
                                                @if ($errors->has('schoolTerm'))
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $errors->first('schoolTerm') }}</strong>
                                                    </span>
                                                @endif

                                                <br /><br />

                                                <input type="submit" name="submit" value="Check Result" class="btn-medium">
                                            </form>
                                            <p class="txt-desc">We are here to serve you better.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-7 col-md-8 col-lg-7 col-lg-offset-1 col-md-mt-100">
                                    <div valign="center" class="header-heading-title">
                                        <!--<a href="{{url('/')}}"><img src="{{ asset('welcomeAssets/images/logo.png') }}" alt="Logo"></a>-->
                                            <h3>
                                                <b class="header-heading-title">SCHOOL E-PORTAL.COM</b>
                                            </h3>
                                    </div>
                                    <div valign="center" class="header-heading-title">
                                        
                                    </div>
                                    <div valign="center" class="header-heading-title">
                                        <h1>Check your result within a click of a button</h1>
                                    </div>
                                    <div valign="center" class="header-heading-title">
                                        <a href="{{$mainWebsiteUrl}}" target="_blank" class="btn btn-danger" style="padding-top:13px;">
                                            <h6>{{ strtoupper('Visit Main School Website') }}</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- .row end -->
                    </div>
                    <!-- .container end -->
                </div>
                <!-- .header-content end -->
            </div>
            <!-- .header-overlay end -->
        </header>
        <!-- #header end -->
            <!-- #counter -->
            <div id="counter" class="bg-img">
                <!-- .bg-overlay -->
                <div class="bg-overlay wrap-container8040">
                    
                    <!-- .container -->
                    <div class="container">
                        
                        <!-- .row -->
                        <div class="row">
                            
                            <div class="col-sm-3"> <!-- 1 -->
                                <div class="affa-counter-txt">
                                    <i class="fa fa-smile-o"></i>
                                    <h4>2560</h4>
                                    <p>Visitors</p>
                                </div>
                            </div>
                            
                            <div class="col-sm-3"> <!-- 2 -->
                                <div class="affa-counter-txt">
                                    <i class="fa fa-eye"></i>
                                    <h4>{{ isset($totalResultViewer) ? $totalResultViewer : 0 }}</h4>
                                    <p>Result Viewers</p>
                                </div>
                            </div>
                            
                            <div class="col-sm-3"> <!-- 3 -->
                                <div class="affa-counter-txt">
                                    <i class="fa fa-users"></i>
                                    <h4>{{ isset($totalStudent) ? $totalStudent : 0 }}</h4>
                                    <p>Students</p>
                                </div>
                            </div>
                            
                            <div class="col-sm-3"> <!-- 4 -->
                                <div class="affa-counter-txt">
                                    <i class="fa fa-users"></i>
                                    <h4>{{ isset($totalTeacher) ? $totalTeacher : 0 }}</h4>
                                    <p>Teachers</p>
                                </div>
                            </div>
                        </div>
                        <!-- .row end -->
                    </div>
                    <!-- .container end -->
                </div>
                <!-- .bg-overlay end -->
                <div class="bg-img-base" style="background-image:url({{ asset('welcomeAssets/images/content/bg/2.jpg')}});"></div> <!-- background image -->
            </div>
            <!-- #counter end -->
            
           
            <!-- #footer -->
            <footer id="footer">
                <!-- .container -->
                <div class="container">
                    <p class="copyright-txt">&copy; {{date('Y')}} Copyrights by <a>Powered by Ajax WebMobile Tech</a> - All rights reserved.</p>
                    <div class="socials">
                        <a href="#" title="Facebook" class="link-facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" title="Twitter" class="link-twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" title="Google Plus" class="link-google-plus"><i class="fa fa-google-plus"></i></a>
                    </div>
                </div>
                <!-- .container end -->
            </footer>
            <!-- #footer end -->
        </div>
        <!-- #main-wrap end -->
    </div>
    <!-- #body-wrap end -->
  
    <!-- JavaScripts -->
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery-1.11.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.easing.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/smoothscroll.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/response.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.placeholder.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.fitvids.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.imgpreload.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/waypoints.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.fancybox.pack.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.fancybox-media.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/jquery.counterup.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('welcomeAssets/js/parallax.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/gmaps.js') }}"></script>
	<script type="text/javascript" src="{{ asset('welcomeAssets/js/script.js') }}"></script>
    
    <script type="text/javascript">
	var map;
	
	map = new window.GMaps({
		div: '#companyMap',
		lat: -12.0411925,
		lng: -77.0282043,
		scrollwheel: false,
		zoomControl: false,
		disableDoubleClickZoom: false,
		disableDefaultUI: true
	});
	
	map.addMarker({
		lat: -12.042,
		lng: -77.028333,
		title: 'Ajax WebMobile Tech',
		infoWindow: {
			content: 'Ajax WebMobile Tech'
		}
	});
    </script>
    
</body>
</html>