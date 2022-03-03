@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strtoupper(__('Edit Student ::'. Auth::user()->name)) }} @endsection
@section('viewAllStudentPageActive') active @endsection
@section('content')
        
        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <form class="form" method="post" action="{{ route('updateStudentDetails') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('UPDATE STUDENT DETAILS') }}</h4>
                            <a href="{{ route('viewAllStudent') }}" class="btn btn-sm btn-secondary"> <i class="fa fa-arrow-left"></i> Cancel</a>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                        
                            <div class="col-md-12 offset-md-0">
                               
	                    		
                                <div class="row">
                                    
                                    <div class="col-md-12" align="center">
                                        <img src="{{ $student ? asset($path . $student->photo) : asset($path . 'noPicture.png') }}" alt=" "  width="80" style="max-height: 130px; max-width: 120px;" class="img-responsive img-thumbnail"/>
                                        <br /><br />
                                    </div>

                                    
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        @if($checkAutoRegNo == 1)
                                                                                            <label for="timesheetinput2 {{ $errors->has('studentRegistrationId') ? ' is-invalid' : '' }}">Student Registration No. <span class="text-danger">*</span> </label>
                                                                                            <div class="position-relative has-icon-left">
                                                                                                <input type="text" value="{{ $student ? $student->student_regID : ''}}" readonly autofocus class="form-control" required placeholder="Auto Reg. Number is Enabled" name="studentRegistrationId">
                                                                                                <input type="hidden" class="form-control" required name="rollNumber" value="{{ $student ? $student->student_roll : '' }}" />
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
                                                                                                        <input type="text" autofocus value="{{ $student ? $student->student_regID : ''}}" class="form-control" required placeholder="Enter student Reg. No." name="studentRegistrationId">
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
                                                                                                        <input type="text" class="form-control" required name="rollNumber" value="{{ $student ? $student->student_roll : '' }}" />
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
                                                                                            <input type="date" value="{{ $student ? $student->admitted_date : '' }}" class="form-control" required placeholder="" name="studentAdmittedDate">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-calendar"></i>
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
                                                                                        <label>Surname/Last Name <span class="text-danger">*</span></label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student ? $student->student_lastname : ''}}" class="form-control" placeholder="Surname/Last Name" name="surname">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Other Name/First Name <span class="text-danger">*</span> </label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student ? $student->student_firstname : ''}}" class="form-control" placeholder="Other Name/First Name" name="otherName">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--//row-->

                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Gender <span class="text-danger">*</span></label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <select class="form-control" name="gender">
                                                                                                <option value="">Select Gender</option>
                                                                                                <option value="Male" {{ ($student ? (strtoupper($student->student_gender) == "MALE" ? "selected" : '') : '') }}>Male</option>
                                                                                                <option value="Female" {{ ($student ? (strtoupper($student->student_gender) == "FEMALE" ? "selected" : '') : '') }}>Female</option>
                                                                                            </select>
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-check"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Student Address (Optional)</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student ? $student->student_address : '' }}" class="form-control" placeholder="Student Address (Optional)" name="studentAddress">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--//row-->

                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Class Name <span class="text-danger">*</span></label>
                                                                                        <div>
                                                                                            <div class="position-relative has-icon-left">
                                                                                                <div class="form-control-position">
                                                                                                    <i class="fa fa-check"></i>
                                                                                                </div>
                                                                                                <select class="form-control" name="className">
                                                                                                    <option value="">Select Class</option>
                                                                                                    @forelse($allClass as $class)
                                                                                                        <option value="{{ $class->classID }}" {{ ($student ? ($class->classID == $student->student_class ? 'selected' : '') : '') }}>{{ $class->class_name}}</option>
                                                                                                    @empty
                                                                                                    @endforelse
                                                                                                </select>                                                                                        </div>
                                                                                                
                                                                                            </div>
                                                                                        <label> Passport Photograph (Optional: <small class="text-warning">png,jpg,jpe,jpeg -Max: 3MB</small>)</label>
                                                                                        <input type="file" class="form-control" name="file" />
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Extra Curricular (Optional)</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <select  multiple class="form-control" name="extraCurricular[]" style="min-height:125px;">
                                                                                                <option value="">None</option>
                                                                                                @forelse($allExtraCurricular as $extra)
                                                                                                    <option value="{{ $extra->curricularID }}"> {{ $extra->curricular_name }}</option>
                                                                                                @empty
                                                                                                @endforelse
                                                                                            </select>
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
                                                                                            <input type="date" name="dateOfBirth" id="dateOfBirth" value="{{ ($student ? $student->date_of_birth : '') }}" class="form-control" placeholder="Student Date of Birth" >
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
                                                                                                <option value="Christianity" {{ ($student ? ($student->religion == 'Christianity' ? 'Selected' : '') : '') }}>Christianity</option>
                                                                                                <option value="Islamic" {{ ($student ? ($student->religion == 'Islamic' ? 'Selected' : '') : '') }}>Islamic</option>
                                                                                                <option value="Other" {{ ($student ? ($student->religion == 'Other' ? 'Selected' : '') : '') }}>Other</option>
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
                                                                                                <span class="text-success">{{ $student ? $student->nationality : '' }}</span>
                                                                                            </div>
                                                                                            <input type="text" style="display:none;" name="nationality" id="nationality" value="{{ $student ? $student->nationality : '' }}" class="form-control" placeholder="Nationality" >
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
                                                                                            <input type="text" name="state" id="state" value="{{ $student ? $student->state : '' }}" class="form-control" placeholder="State of origin" >
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
                                                                                            <input type="text" name="homeTown" id="homeTown" value="{{ $student ? $student->home_town : '' }}" class="form-control" placeholder="Student Home Town" >
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
                                                                                                    <option value="{{$schlType->schooltypeID}}" {{ $student ? ($student->school_type == $schlType->schooltypeID ? 'selected' : '') : '' }}>{{$schlType->school_type_name}}</option>
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
                                                                                        <label>First Name</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student->parent_firstname }}" class="form-control" placeholder="" name="parentFirstName">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Last Name</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student->parent_lastname }}" class="form-control" name="parentLastName">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--//row-->

                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Telephone Number</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student->parent_telephone }}" class="form-control" name="parentTelephone">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-phone"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Address</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student->parent_address }}" class="form-control" name="parentAddress">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--//row-->

                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Email Address</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="email" value="{{ $student->parent_email }}" class="form-control" name="parentEmail">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-envelope"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group col-md-6 mb-2">
                                                                                        <label>Occupation</label>
                                                                                        <div class="position-relative has-icon-left">
                                                                                            <input type="text" value="{{ $student->parent_occupation }}" class="form-control" name="parentOccupation">
                                                                                            <div class="form-control-position">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--//row-->
                                                                                </div><!--//toggle parent-->

                                <div class="row">
	                    			<div class="form-group col-12 mb-2">
                                    <hr />
                                        <input type="hidden" name="studentID"  value="{{ $student->studentID }}" />
			                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Update') }}</button>
                                    </div>
								</div><!--//row-->
                            </div><!--//col-8-->
                        </form>

                        
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