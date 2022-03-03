<!-- NAVBAR -->
      <nav id="menuBar" class="navbar navbar-default lightHeader" role="navigation" style="background: #8DBF41;">
        <div class="row" style="margin-top: -22px;">
          <!-- Brand and toggle get grouped for better mobile display --container-->
            <div>&nbsp;&nbsp;&nbsp;</div>
            <div align="right" class="col-md-1 hidden-sm hidden-xs">
                  
            </div>

            <div align="right" class="col-md-3 col-sm-9 col-xs-5 hidden-lg hidden-md hidden-sm">
                  <a href="{{route('index')}}">
                    <img src="{{asset('site-assets/images/logo.png')}}" class="img-responsive" alt="Supreme Education">
                  </a>
            </div>

            <div class="col-xs-4 hidden-lg hidden-md hidden-sm">
              &nbsp;
            </div>

            <div class="navbar-header">
              <div align="right" class="hidden-md col-sm-3 col-xs-3 ">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse" style="margin-top: 5px; background: green; border: 1px solid #ddd">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
              </div> <!--class="navbar-brand"-->
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="col-md-8 collapse navbar-collapse navbar-ex1-collapse" >
            <ul class="nav navbar-nav navbar-right">
              <!--Home-->
              <li class="dropdown menu-padding singleDrop color-4 @yield('indexActive')">
                <a href="{{route('index')}}">
                  <font class="text-upper"> Home </font>
                </a>
              </li>
              <!--About-->
              <li class="dropdown menu-padding singleDrop color-4 @yield('aboutActive')">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <font class="text-upper">About Us</font>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li class=" "><a href="{{route('history')}}">Why Choose Us</a></li>
                    <li class=""><a href="{{route('founderMessage')}}">Founder's Welcome Message</a></li>
                    <li class=""><a href="{{route('ceoMessage')}}">CEO's Welcome Message</a></li>
                    <li class=" "><a href="{{route('visionMission')}}">Vision & Mission</a></li>
                    <li class=" "><a href="{{route('schoolAnthem')}}">School Anthem</a></li>
                    <li class=" "><a href="{{route('faq')}}">FAQs</a></li>
                  </ul>
            </li>
            <!--Admissions-->
             <li class="dropdown menu-padding singleDrop color-4 @yield('admissionActive')">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <font class="text-upper">Admissions</font>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li class=" "><a href="{{route('admissionProcedure')}}">Admission Procedure</a></li>
                    <li class=" "><a href="{{route('entranceExamination')}}">Entrance Examination</a></li>
                    <li class=" "><a href="{{route('applicationForm')}}">Application Form</a></li>
                    <li class=" "><a href="{{route('onlineApplicationForm')}}">Online Application Form</a></li>
                  </ul>
            </li>
            <!--Academics-->
             <li class="dropdown singleDrop menu-padding  color-4 @yield('academicsActive')">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <font class="text-upper">Academics</font>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li class=" "><a href="{{route('academicsCalendar')}}">Academics Calendar</a></li>
                    <li class=" "><a href="{{route('ourCurriculum')}}">Our Curriculum</a></li>
                    <li class=" "><a href="{{route('coCurriculum')}}">Co-Curricular</a></li>
                    <li class=" "><a href="{{route('CrecheNurserySchool')}}">Creche/Nursery School</a></li>
                    <li class=" "><a href="{{route('juniorSchool')}}">Junior School</a></li>
                    <li class=" "><a href="{{route('middleSchool')}}">Middle School</a></li>
                    <li class=" "><a href="{{route('highSchool')}}">High School</a></li>
                    <li class=" "><a href="{{route('sport')}}">Sports</a></li>
                    <li class=" "><a href="{{route('lifeAfterSef')}}">Life After SEF</a></li>
                  </ul>
            </li>

             <!--Facilities-->
             <li class="dropdown singleDrop menu-padding  color-4 @yield('facilityActive')">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <font class="text-upper">Facilities</font>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li class=" "><a href="{{route('virtualTour')}}">Virtual Tour</a></li>
                    <li class=" "><a href="{{route('boarding')}}">Boarding</a></li>
                    <li class=" "><a href="{{route('medicalService')}}">Medical Service</a></li>
                    <li class=" "><a href="{{route('library')}}">Library</a></li>
                  </ul>
            </li>

            <!--News and Events-->
             <li class="dropdown singleDrop menu-padding  color-4 @yield('newsEventsActive')">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <font class="text-upper"> News/Events </font>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li class=" "><a href="{{route('newsEvents')}}">Recent News</a></li>
                    <li class=" "><a href="{{route('newsletter')}}">Newsletter</a></li>
                  </ul>
            </li>

              <!--Media-->
                <li class="dropdown singleDrop menu-padding  color-4 @yield('mediaActive')">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <font class="text-upper">Media</font>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li class=" "><a href="{{route('gallery')}}">Gallery</a></li>
                      <li class=" "><a href="https://www.youtube.com/channel/UC_TRGhR8mnQ8NZyp1ml7YWw" target="_blank">Youtube</a></li>
                      <li class=" "><a href="https://www.flickr.com/photos/154045882@N04/albums/with/72157685561956873" target="_blank">Flickr Photo</a></li>
                      <li class=" "><a href="{{route('smec')}}">SMEC {{date('Y')}} Results</a></li>
                      <li class="dropdown dropdown-submenu">
                        <a href="#">Document <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li class=" "><a href="{{route('yearBook')}}">Year Book</a></li>
                            <li class=" "><a href="{{route('download')}}">Other Downloads</a></li>
                        </ul>
                      </li>
                    </ul>
                 </li>

              <!--Blog-->
               <li class="menu-padding singleDrop color-4 @yield('blogActive')">
                <a href="{{route('blog')}}">
                  <font class="text-upper"> Blog </font>
                </a>
              </li>

              <!--COntact US-->
               <li class="singleDrop menu-padding color-4 @yield('contactActive')" >
                    <a href="{{route('contact')}}" role="button" aria-haspopup="true" aria-expanded="false">
                        <font class="text-upper"> Contact us </font>
                    </a>
              </li>
          
            </ul>
          </div>
        </div>
        <!--include('PartialView.coloured-line')-->
      </nav>
