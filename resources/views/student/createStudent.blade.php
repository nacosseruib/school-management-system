@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strtoupper(__('Create Student ::'. Auth::user()->name)) }} @endsection
@section('studentPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <form class="form d-print-none" method="post" action="{{ route('createStudent') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CREATE STUDENT') }}</h4>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                            <div class="col-md-12">
                                
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
	                    		
                                <div class="row">
	                    			<div class="form-group col-md-6 mb-2">
                                        @if($checkAutoRegNo == 1)
                                            <label for="timesheetinput2 {{ $errors->has('studentRegistrationId') ? ' is-invalid' : '' }}">Student Registration No. <span class="text-danger">*</span> </label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" value="{{ $registrationNo }}" readonly autofocus class="form-control" required placeholder="Auto Reg. Number is Enabled" name="studentRegistrationId">
                                                <div class="form-control-position">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                @if ($errors->has('studentRegistrationId'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('studentRegistrationId') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="timesheetinput2 {{ $errors->has('studentRegistrationId') ? ' is-invalid' : '' }}">Student Registration No. <span class="text-danger">*</span> </label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" autofocus value="{{ old('studentRegistrationId') }}" class="form-control" required placeholder="Enter student Reg. No." name="studentRegistrationId">
                                                        <div class="form-control-position">
                                                            <i class="fa fa-user"></i>
                                                        </div>
                                                        @if ($errors->has('studentRegistrationId'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('studentRegistrationId') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="rollNumber {{ $errors->has('rollNumber') ? ' is-invalid' : '' }}">Roll No.<span class="text-danger">*</span> </label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" class="form-control" required name="rollNumber" value="{{ old('rollNumber') }}" />
                                                        <div class="form-control-position">
                                                            <i class="fa fa-user"></i>
                                                        </div>
                                                        @if ($errors->has('rollNumber'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('rollNumber') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                        </div><!--//row-->
                                       @endif
                                    </div><!--//col-6-->
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('studentAdmittedDate') ? ' is-invalid' : '' }}">Admission Date <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="date" value="{{old('studentAdmittedDate')}}" class="form-control" required placeholder="" name="studentAdmittedDate">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('studentAdmittedDate'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('studentAdmittedDate') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
								</div><!--//row-->
                                
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('surname') ? ' is-invalid' : '' }}">Surname/Last Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('surname')}}" class="form-control" required placeholder="Surname/Last Name" name="surname">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('surname'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('surname') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('otherName') ? ' is-invalid' : '' }}">Other Name/First Name <span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('otherName')}}" class="form-control" required placeholder="Other Name/First Name" name="otherName">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('otherName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('otherName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('gender') ? ' is-invalid' : '' }}">Gender <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ (old('gender') == 'Male') ? 'Selected' : '' }}>Male</option>
                                                <option value="Female" {{ (old('gender') == 'Female') ? 'Selected' : '' }}>Female</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('gender'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('studentAddress') ? ' is-invalid' : '' }}">Student Address (Optional)</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('studentAddress')}}" class="form-control" placeholder="Student Address (Optional)" name="studentAddress">
				                            <div class="form-control-position">
				                                <i class="fa fa-map"></i>
                                            </div>
                                            @if ($errors->has('studentAddress'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('studentAddress') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('className') ? ' is-invalid' : '' }}">Class Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="className">
                                                <option value="">Select Class</option>
                                                @forelse($allClass as $class)
                                                    <option value="{{ $class->classID }}" {{ (old('className') == $class->classID) ? 'Selected' : '' }}>{{ $class->class_name}}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('className'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('className') }}</strong>
                                                </span>
                                            @endif
			                            </div>

                                        <label class="mt-2"> Passport Photograph (<small class="text-warning">png,jpg,jpe,jpeg -Max: 3MB</small>)</label>
                                        <input type="file" class="form-control" name="file" />
                                    </div> 
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('extraCurricular') ? ' is-invalid' : '' }}">Extra Curricular (Optional)</label>
			                            <div class="position-relative has-icon-left">
                                            <select  multiple class="form-control" name="extraCurricular[]" style="height:140px;">
                                                <option value="">None</option>
                                                @forelse($allExtraCurricular as $extra)
                                                    <option value="{{ $extra->curricularID }}">{{ $extra->curricular_name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('extraCurricular'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('extraCurricular') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="dateOfBirth {{ $errors->has('dateOfBirth') ? ' is-invalid' : '' }}">Date of Birth</label>
			                            <div class="position-relative has-icon-left">
                                            <input type="date" name="dateOfBirth" id="dateOfBirth" value="{{old('dateOfBirth')}}" class="form-control" placeholder="Student Date of Birth" >
                                            <div class="form-control-position">
				                                <i class="fa fa-calendar"></i>
                                            </div>
                                            @if ($errors->has('dateOfBirth'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('dateOfBirth') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="religion {{ $errors->has('religion') ? ' is-invalid' : '' }}">Religion</label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="religion">
                                                <option value="">Select</option>
                                                <option value="Christianity" {{ (old('religion') == 'Christianity') ? 'Selected' : '' }}>Christianity</option>
                                                <option value="Islamic" {{ (old('religion') == 'Islamic') ? 'Selected' : '' }}>Islamic</option>
                                                <option value="Other" {{ (old('religion') == 'Other') ? 'Selected' : '' }}>Other</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('religion'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('religion') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                </div><!--//row-->
                                
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="nationality {{ $errors->has('nationality') ? ' is-invalid' : '' }}">Nationality</label>
			                            <div class="position-relative has-icon-left">
			                            	<div class="niceCountryInputSelector" data-selectedcountry="US" data-showspecial="false" data-showflags="true" data-i18nall="All selected"
                                                data-i18nnofilter="No selection" data-i18nfilter="Filter" data-onchangecallback="onChangeCallback" />
                                            </div>
                                            <input type="text" style="display:none;" name="nationality" id="nationality" value="{{old('nationality')}}" class="form-control" placeholder="Nationality" >
                                            @if ($errors->has('nationality'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('nationality') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                     <div class="form-group col-md-6 mb-2">
			                            <label for="state {{ $errors->has('state') ? ' is-invalid' : '' }}">State</label>
			                            <div class="position-relative has-icon-left">
                                            <input type="text" name="state" id="state" value="{{old('state')}}" class="form-control" placeholder="State of origin" >
                                            <div class="form-control-position">
				                                <i class="fa fa-calendar"></i>
                                            </div>
                                            @if ($errors->has('state'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                
                                 <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="homeTown {{ $errors->has('homeTown') ? ' is-invalid' : '' }}">Home Town</label>
			                            <div class="position-relative has-icon-left">
                                            <input type="text" name="homeTown" id="homeTown" value="{{old('homeTown')}}" class="form-control" placeholder="Student Home Town" >
				                            <div class="form-control-position">
				                                <i class="fa fa-map"></i>
                                            </div>
                                            @if ($errors->has('homeTown'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('homeTown') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="schoolType {{ $errors->has('schoolType') ? ' is-invalid' : '' }}">School Type</label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="schoolType">
                                                <option value="">Select</option>
                                                @if(isset($schoolType) and $schoolType)
                                                    @foreach($schoolType as $schlType)
                                                    <option value="{{$schlType->schooltypeID}}">{{$schlType->school_type_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('schoolType'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolType') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                </div><!--//row-->
                                

                                <!--Student Parent Details-->
                                <div class="row">
                                    <div class="form-group col-md-10 mb-2">
                                        <div class="form-control text-info">PARENT's INFORMATION (Optional)</div>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <div align="center" style="cursor:pointer;" title="Add parent information" id="showHideParentDetails" class="text-info">
                                            <i class="fa fa-user fa-2x"></i><br /> <span class="text-success"><small><small>Add Parent</small></small></span>
                                        </div>
                                    </div>
                                </div>
                                <div id="toggleParent">
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('parentFirstName') ? ' is-invalid' : '' }}">First Name</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('parentFirstName')}}" class="form-control" placeholder="" name="parentFirstName">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('parentFirstName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('parentFirstName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('parentLastName') ? ' is-invalid' : '' }}">Last Name</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('parentLastName')}}" class="form-control" placeholder="" name="parentLastName">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('parentLastName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('parentLastName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('parentTelephone') ? ' is-invalid' : '' }}">Telephone Number</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('parentTelephone')}}" class="form-control" placeholder="" name="parentTelephone">
				                            <div class="form-control-position">
				                                <i class="fa fa-mobile"></i>
                                            </div>
                                            @if ($errors->has('parentTelephone'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('parentTelephone') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('parentAddress') ? ' is-invalid' : '' }}">Address</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('parentAddress')}}" class="form-control" placeholder="" name="parentAddress">
				                            <div class="form-control-position">
				                                <i class="fa fa-map"></i>
                                            </div>
                                            @if ($errors->has('parentAddress'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('parentAddress') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('parentEmail') ? ' is-invalid' : '' }}">Email Address</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="email" value="{{old('parentEmail')}}" class="form-control" placeholder="" name="parentEmail">
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('parentEmail'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('parentEmail') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('parentOccupation') ? ' is-invalid' : '' }}">Occupation</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('parentOccupation')}}" class="form-control" placeholder="" name="parentOccupation">
				                            <div class="form-control-position">
				                                <i class="fa fa-gear"></i>
                                            </div>
                                            @if ($errors->has('parentOccupation'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('parentOccupation') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                </div><!--//toggle parent-->

                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
                                    <hr />
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Add') }}</button>
                                    </div>
								</div><!--//row-->
                            </div><!--//col-8-->
                        </form>

                        <div align="center" class="col-md-12 offset-md-0">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead style="background:#d9d9d9; font-size:12px;">
                                <tr>
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('Reg. No') }}</th>
                                    <th>{{ __('Roll') }}</th>
                                    <th>{{ __('Surname Name') }}</th>
                                    <th>{{ __('Other Names') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('CLass Name') }}</th>
                                    <th>{{ __('Admitted Date') }}</th>
                                    <th>{{ __('Parent Telephone') }}</th>
                                    <th>{{ __('Created Date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody style="font-size:12px;">
                                @forelse($allStudent as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->student_regID) }}</th>
                                    <th>{{ __($list->student_roll) }}</th>
                                    <th>{{ __($list->student_lastname) }}</th>
                                    <th>{{ __($list->student_firstname) }}</th>
                                    <th>{{ __($list->student_gender) }}</th>
                                    <th>{{ __($list->class_name) }}</th>
                                    <th>{{ __($list->admitted_date) }}</th>
                                    <th>{{ __($list->parrent_telephone) }}</th>
                                    <th>{{ __($list->studentDate) }}</th>
                                    <th><a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="false" data-target="#deleteStudent{{$list->studentID}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade text-left" id="deleteStudent{{$list->studentID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Student')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->student_lastname.' '. $list->student_firstname .' - '. $list->student_regID) }} ! </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __("This record will be moved to student's recycle bin !")}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeStudent', [$list->studentID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Modal-->

                                @empty
                                    <tr><td colspan="6" class="text-danger">{{ __('No record found!') }}</td></tr>
                                @endforelse
                            </tbody>
                            </table>
                            <div class="row">
                              <div align="right" class="col-xs-12 col-sm-12">
                                  Showing {{($allStudent->currentpage()-1)*$allStudent->perpage()+1}}
                                  to {{$allStudent->currentpage()*$allStudent->perpage()}}
                                  of  {{$allStudent->total()}} entries
                                  <br />
                                  <div class="hidden-print">{{ $allStudent->links() }}</div> 
                              </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('styles')
    <!--Get All Country-->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script src="{{asset('appAssets/app/select-country/niceCountryInput.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('appAssets/app/select-country/niceCountryInput.css')}}">
    <!--end Get All Country-->
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            $('#toggleParent:visible').hide();
            $("#showHideParentDetails" ).click(function() { 
                $("#toggleParent").toggle();
            });
        });
    </script>

    <!--Get All Country-->
     <script>
        function onChangeCallback(ctr){
            console.log("The country was changed: " + ctr);
            $("#nationality").val(ctr);
        }

        $(document).ready(function () {
            $(".niceCountryInputSelector").each(function(i,e){
                new NiceCountryInput(e).init();
            });
        });
    </script>
    <!--end Get All Country-->
@endsection