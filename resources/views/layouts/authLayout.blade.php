<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="A powerful school application for all your school activities, Software for school result, generate PIN, check result student report card online with PIN.">
    <meta name="keywords" content="School HR, School E-Portal, School Result Application, Check result, parent, staff, teacher, principal, School Result, Result, Result different template, Report card, Report sheet, Generate PIN for result">
    <meta name="author" content="Samson Ajax">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('pageHeaderTitle') </title>
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('appAssets/app/app-assets/img/ico/apple-icon-60.html') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('appAssets/app/app-assets/img/ico/apple-icon-76.html') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('appAssets/app/app-assets/img/ico/apple-icon-120.html') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('appAssets/app/app-assets/img/ico/apple-icon-152.html') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('appAssets/schoolLogo/'. (Session::get('getSchoolProfile') ? Session::get('getSchoolProfile')->logo : '') )}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900|Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/fonts/feather/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/vendors/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/vendors/css/prism.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('appAssets/app/app-assets/css/app.css') }}">

    <script type="text/javascript" src="{{ asset('appAssets/app/app-assets/js/number_to_word.js') }}"></script>

    <script src="{{ asset('appAssets/app/app-assets/vendors/js/core/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script>
    //Change type of student you want to search for
    $(document).ready(function() {
        $('#searchStudentType').change(function() {
          var getStudentType = $('#searchStudentType').val();
          if (getStudentType == "" || getStudentType < 0)
          {
              alert('Please select type of student you want to search for!!!');
              return false;
          }else{
            $.ajax({
                url: '{{url("/")}}' +  '/search_current_graduate_student_json/' + getStudentType,
                type: 'get',
                //data: { format: 'json' },
                //dataType: 'json',
                success: function(data) {
                  location.reload();
                },
                error: function(error) {
                  alert("Please we are having issue getting the type of student you want to search for! Check your network/refresh this page !!!");
                }
            });
          }//
        });
    });//end ready
   </script>

    @yield('styles')
  </head>
  <body onload="lookup();"> <!--data-col="2-columns" class="2-columns"-->
    <div class="wrapper">
      <div class="app-sidebar d-print-none" data-active-color="white" data-background-color="black" data-image="@if(Session::get('getSchoolProfile')) {{ asset('appAssets/schoolLogo/'. (Session::get('getSchoolProfile') ? Session::get('getSchoolProfile')->logo : '') )}} @endif">
        <div class="sidebar-header  d-print-none">
          <div class="logo clearfix">
            <a href="{{ route('home') }}" class="logo-text float-left">
              <div class="logo-img">
              @if(Session::get('getSchoolProfile'))
                  <img src="@if(Session::get('getSchoolProfile')) {{ asset('appAssets/schoolLogo/'. (Session::get('getSchoolProfile') ? Session::get('getSchoolProfile')->logo : '') )}} @else {{ asset('appAssets/schoolLogo/default.png' )}} @endif" alt=" "  width="30" class="img-responsive"/>
              @else
                  <img src="{{ asset('appAssets/schoolLogo/default.png' )}}" alt=" " width="30" class="img-responsive"/>
              @endif
              </div>
              <span class="text align-middle">
                    <b>@if(Session::get('getSchoolProfile')) {{ Session::get('getSchoolProfile')->school_short_name  }} @else SRC @endif</b>
                    <br />
                    <small style="font-size:6px;">@if(Session::get('getSchoolProfile')) {{ Session::get('getSchoolProfile')->email }} @endif</small>
                    <br />
                    <b>
                    @if(Auth::check())
                        <b style="font-size:13px;">
                            {{ (Session::get('roleName') ? Session::get('roleName') : '') }}
                        </b>
                        <br />
                        <small style="font-size:9px;">
                            <span class="text-success">
                                Last Login: {{ Auth::User()->last_login }}
                            </span>
                        </small>
                    @else
                      <small style="font-size:10px;">Welcome Parent !</small>
                    @endif
                  </b>
              </span>
            </a>
            <a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block">
                <i data-toggle="expanded" class="ft-toggle-right toggle-icon"></i>
            </a>
            <a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none">
                <i class="ft-x"></i>
            </a>
        </div>
        </div>

        <!-- Sidebar Header Ends-->
        <!-- / main menu header-->
        <!-- main menu content-->
        <div class="sidebar-content  d-print-none">
          <div class="nav-container">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
            @if(Auth::check())
              <li class="has-sub nav-item"><a href="#"><i class="ft-home"></i><span data-i18n="" class="menu-title">{{ __('Dashboard') }}</span><span class="tag badge badge-pill badge-danger float-right mr-1 mt-1">2</span></a>
                <ul class="menu-content">
                    <li class="@yield('homePageActive')"><a href="{{ route('home') }}" class="menu-item"><i class="ft-star"></i> {{ __('Home') }}</a></li>
                    <li><a href="{{ route('logout') }}" class="menu-item"><i class="ft-star"></i> {{ __('Logout') }}</a></li>
                </ul>
              </li>
            @else
                <li class="has-sub nav-item"><a href="#"><i class="ft-home"></i><span data-i18n="" class="menu-title">{{ __('Dashboard') }}</span><span class="tag badge badge-pill badge-danger float-right mr-1 mt-1">2</span></a>
                  <ul class="menu-content">
                      <li class="@yield('homePageActive')"><a href="{{ route('viewStudentReport') }}" class="menu-item"><i class="ft-star"></i> {{ __('Home') }}</a></li>
                      <li><a href="{{ route('parentLogout') }}" class="menu-item"><i class="ft-star"></i> {{ __('Logout') }}</a></li>
                  </ul>
                </li>
            @endif

              @if(Session::get('userMenuModule'))
                  @foreach(Session::get('userMenuModule') as $key=>$module)
                    <li class="has-sub nav-item"><a href="{{($module->module_url == '#' or $module->module_url == '') ? $module->module_url : route($module->module_url) }}"><i class="{{ $module->module_icon }}"></i><span data-i18n="" class="menu-title">{{ $module->module_name }}</span></a>
                      <ul class="menu-content">
                          @foreach(Session::get('userMenu')[$key.$module->moduleID] as $subModule)
                            <li class="@yield($subModule->submodule_active_page, $subModule->submodule_active_page) nav-item">
                              <a href="{{ ($subModule->submodule_url =='#' or $subModule->submodule_url =='') ? $subModule->submodule_url : route($subModule->submodule_url) }}" class="menu-item"><i class="{{ $subModule->submodule_icon }}"></i> <span data-i18n="" class="menu-title"> {{ $subModule->submodule_name }} </span></a>
                            </li>
                          @endforeach
                      </ul>
                    </li>
                  @endforeach
                  <li><br /><br /><br /><br /><br /><br /></i>
              @endif

            </ul>
          </div>
        </div>
        <!-- main menu content-->

        <div class="sidebar-background d-print-none"></div>
      </div>
      <!-- / main menu-->

      @if(Auth::check())
      <!-- Navbar (Header) Starts-->
      <nav style="background-image: url({{asset('appAssets/login/images/bg-01.jpg')}});" class="navbar navbar-expand-lg navbar-light bg-faded header-navbar d-print-none">
        <div class="container-fluid d-print-none">

        <div class="navbar-header">
            <button type="button" data-toggle="collapse" class="navbar-toggle d-lg-none float-left">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="d-lg-none navbar-right navbar-collapse-toggle">
                <a aria-controls="navbarSupportedContent" href="javascript:;" class="open-navbar-container black">
                  <i class="ft-more-vertical"></i>
                </a>
            </span>

            <div class="text-center" style="color:#FFF;">
                    <a href="{{ route('home') }}">
                    @if(Session::get('getSchoolProfile'))
                      <img src="@if(Session::get('getSchoolProfile')) {{ asset('appAssets/schoolLogo/'. (Session::get('getSchoolProfile') ? Session::get('getSchoolProfile')->logo : '') )}} @else {{ asset('appAssets/schoolLogo/default.png' )}} @endif"
                        alt=" " style="max-width: 35px;"/>
                    @else
                        <img src="{{ asset('appAssets/schoolLogo/default.png' )}}" alt=" " style="max-width: 35px;"/>
                    @endif
                    </a>
                    <span style="font-weight:bolder; font-size:26px;">
                      <b><b>
                      {{ (Session::get('getSchoolProfile') <> null) ? strtoupper( Session::get('getSchoolProfile')->school_full_name  ) : strtoupper('School Result Computation') }}
                      </b></b>
                    </span>

                   <div class="row" align="center">
                        <div class="col-md-6 text-center">
                           <i><small>{{ (Session::get('getSchoolProfile') <> null) ? strtoupper(substr(Session::get('getSchoolProfile')->slogan, 0, 60)) : ''}}...</small></i>
                        </div>
                        <div class="col-md-6 text-center">
                            <b><i><small>@if(Session::get('getSchoolSession') <> null) {{ strtoupper( Session::get('getSchoolSession')->session_name  )}} @else {{ strtoupper('School Session not set!') }} @endif</small></i></b>
                            <span style="color:white;">@if(Session::get('getSchoolProfile') <> null) {{ Session::get('getSchoolProfile')->phone_no }} @endif</span>
                        </div>
                   </div>
              </div>
          </div>

          <div class="navbar-container d-print-none">
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
              <ul class="navbar-nav">

              <li>
                @if(Auth::check())
                  <span style="color:#FFF"><b>{{ strtoupper(Auth::User()->name .' '. Auth::User()->other_name) }}</b></span> &nbsp;
                @else
                <div class="text-danger" style="background: #FFF; padding:0 5px;">{{ __('Session has expired! Login now.') }}</div>
                @endif
              </li>

                <li class="nav-item mr-2 d-none d-lg-block"><a id="navbar-fullscreen" href="javascript:;" class="nav-link apptogglefullscreen"><i class="ft-maximize font-medium-3 blue-grey darken-4"></i>
                    <p class="d-none">Fullscreen</p></a>
                </li>
                <li class="dropdown nav-item"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-user font-medium-3 blue-grey darken-4"></i><span class="selected-language d-none"></span></a>
                <p class="d-none">User Settings</p></a>
                    <div ngbdropdownmenu="" aria-labelledby="dropdownBasic3" class="dropdown-menu text-left dropdown-menu-right">
                        <a href="{{ route('updateProfile') }}" class="dropdown-item py-1">
                            <i class="ft-edit mr-2"></i><span>Edit Profile</span>
                        </a>
                    <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="ft-power mr-2"></i><span>{{ __('Logout') }}</span>
                        </a>
                  </div>
                </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
              </ul>
            </div>

          </div>
        </div>
      </nav>
      <!-- Navbar (Header) Ends-->
      @endif


      <div class="main-panel">

        <main>
            @yield('content')
        </main>

        <footer class="footer footer-static footer-light d-print-none" style="background-color: black; padding:10px;">
          <p class="clearfix text-muted text-sm-center px-2">
              <span>Copyright  &copy; {{date('Y')}} <a herf="https//:schooleportal.com" target="_blank">Ajax WebMobile Tech. </a> All rights reserved.
                    <small class="text-danger">
                        For any Technical Support/Complain: info@schooleportal.com
                    </small>
            </span>
          </p>
        </footer>
      </div>
    </div>

        <!-- Sidebar Width Ends-->
      </div>
    </div>
    <!-- Theme customizer Ends-->
    <!-- BEGIN VENDOR JS-->

    <script src="{{ asset('appAssets/app/app-assets/vendors/js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/prism.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/jquery.matchHeight-min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/screenfull.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/pace/pace.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/chartist.min.js') }}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN APEX JS-->
    <script src="{{ asset('appAssets/app/app-assets/js/app-sidebar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/js/notification-sidebar.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/js/customizer.js') }}" type="text/javascript"></script>
    <!-- END APEX JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="{{ asset('appAssets/app/app-assets/js/dashboard2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/js/components-modal.min.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
    <script src="{{ asset('appAssets/app/app-assets/vendors/js/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('appAssets/app/app-assets/js/data-tables/datatable-basic.js') }}" type="text/javascript"></script>
    @yield('scripts')
  </body>
</html>
