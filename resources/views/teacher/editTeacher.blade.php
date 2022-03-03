@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strtoupper(__('Update Teacher' )) }} @endsection
@section('viewAllTeacherPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <form class="form hide-print" method="post" action="{{ route('updateTeacher') }}" enctype="multipart/form-data"> 
                    @csrf
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('UPDATE TEACHER/USER DETAILS') }}</h4>
                            <a href="{{ route('viewAllTeacher') }}" class="btn btn-sm btn-secondary"> <i class="fa fa-arrow-left"></i> Cancel</a>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                        
                            <div class="col-md-10 offset-md-1">
                                
                                <div class="row">
                                    <div class="col-md-12" align="center">
                                        <img src="{{( ($teacher->photo) ? asset($path . $teacher->photo ) : asset($path . 'noPicture.png') )}}" alt=" "  width="80" class="img-responsive img-thumbnail"/>
                                        <br /><br />
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('surname') ? ' is-invalid' : '' }}">Surname/Last Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input autofocus type="text" value="{{ $teacher->name }}" class="form-control" required placeholder="Surname/Last Name" name="surname">
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
			                            	<input type="text" value="{{$teacher->other_name}}" class="form-control" required placeholder="Other Name/First Name" name="otherName">
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
			                            <label for="timesheetinput2 {{ $errors->has('userRegistrationId') ? ' is-invalid' : '' }}">User RegID (Auto Generate) <span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<div readonly class="form-control">{{$teacher->userRegistrationId}}</div>
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('userRegistrationId'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('userRegistrationId') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('admittedDate') ? ' is-invalid' : '' }}">Admitted Date <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="date" value="{{$teacher->admitted_date}}" class="form-control" required placeholder="" name="admittedDate">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('admittedDate'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('admittedDate') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
								</div><!--//row-->
                                
                                <div class="row">
                                    <div class="form-group col-md-3 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('gender') ? ' is-invalid' : '' }}">Gender <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="gender">
                                                <option>{{$teacher->gender}}</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
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
                                    <div class="form-group col-md-3 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('phoneNumber') ? ' is-invalid' : '' }}">Phone Number </label>
			                            <div class="position-relative has-icon-left">
                                        <input type="text" value="{{$teacher->telephone}}" class="form-control" placeholder="Phone Number (Optional)" name="phoneNumber">
                                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('phoneNumber'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('phoneNumber') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('address') ? ' is-invalid' : '' }}">Address (Optional)</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{$teacher->address}}" class="form-control" placeholder="Address (Optional)" name="address">
				                            <div class="form-control-position">
				                                <i class="fa fa-map"></i>
                                            </div>
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-2">
                                                <label for="timesheetinput2 {{ $errors->has('designation') ? ' is-invalid' : '' }}">Designation <span class="text-danger">*</span></label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" required name="designation">
                                                        <option>{{$teacher->designation}}</option>
                                                        <option>Principal</option>
                                                        <option>Head Teacher</option>
                                                        <option>Class Teacher</option>
                                                        <option>Teacher</option>
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    @if ($errors->has('designation'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('designation') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 mb-2">
                                                <label for="timesheetinput2 {{ $errors->has('userType') ? ' is-invalid' : '' }}">User Type <span class="text-danger">*</span></label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" required name="userType">
                                                        <option value="{{$teacher->roleID}}">{{$teacher->role_name}}</option>
                                                        @forelse($allRole as $userRole)
                                                            <option value="{{ $userRole->roleID }}" {{ ($userRole->roleID == old('roleName') ? 'slected' : '' )}}>{{ __($userRole->role_name) }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    @if ($errors->has('userType'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('userType') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <label> Passport Photograph (Optional: <small class="text-warning">png,jpg,jpe,jpeg -Max: 3MB</small>)</label>
                                        <input type="file" class="form-control" name="file" />

                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('teacherClass') ? ' is-invalid' : '' }}">Select Teacher's Class(es)</label>
			                            <div class="position-relative has-icon-left">
                                            <select  multiple  class="form-control" name="teacherClass[]" style="min-height:135px;">
                                                <option value="0">None</option>
                                                @forelse($allClass as $class)
                                                    <option value="{{ $class->classID }}">{{ $class->class_name}}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('teacherClass'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('teacherClass') }}</strong>
                                                </span>x
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <!--Teacher Login Details-->
                                <div class="row" style="background-color: #ddd;">
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('email') ? ' is-invalid' : '' }}">Offical Email (Login ID) <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="email" required value="{{$teacher->email}}" class="form-control" placeholder="Email Address" name="email">
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('password') ? ' is-invalid' : '' }}">Password</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="password" class="form-control" value="" placeholder="Password" name="password">
				                            <div class="form-control-position">
				                                <i class="fa fa-key"></i>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">Confirm Password</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="password" class="form-control" value="" placeholder="Password" name="password_confirmation">
				                            <div class="form-control-position">
				                                <i class="fa fa-key"></i>
                                            </div>
                                            @if ($errors->has('password_confirmation'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                <br />
                                <!--Teacher Guarantor Details-->
                                <div class="row"> 
                                    <div class="form-group col-md-10 mb-2">
                                        <div class="form-control text-info">Teacher's Guarantor Details (Optional)</div>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <div align="center" style="cursor:pointer;" title="Add parent information" id="showHideParentDetails" class="text-info">
                                            <i class="fa fa-user fa-2x"></i><br /> <span class="text-success"><small><small>Add Guarantor</small></small></span>
                                        </div>
                                    </div>
                                </div>
                                <div id="toggleParent">
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('guarantorFirstName') ? ' is-invalid' : '' }}">First Name</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{$teacher->guarantor_firstname}}" class="form-control" placeholder="" name="guarantorFirstName">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('guarantorFirstName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('guarantorFirstName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('guarantorLastName') ? ' is-invalid' : '' }}">Last Name</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{$teacher->guarantor_lastname}}" class="form-control" placeholder="" name="guarantorLastName">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('guarantorLastName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('guarantorLastName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('guarantorTelephone') ? ' is-invalid' : '' }}">Telephone Number</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{$teacher->guarantor_telephone}}" class="form-control" placeholder="" name="guarantorTelephone">
				                            <div class="form-control-position">
				                                <i class="fa fa-mobile"></i>
                                            </div>
                                            @if ($errors->has('guarantorTelephone'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('guarantorTelephone') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('guarantorAddress') ? ' is-invalid' : '' }}">Address</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{$teacher->guarantor_address}}" class="form-control" placeholder="" name="guarantorAddress">
				                            <div class="form-control-position">
				                                <i class="fa fa-map"></i>
                                            </div>
                                            @if ($errors->has('guarantorAddress'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('guarantorAddress') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('guarantorEmail') ? ' is-invalid' : '' }}">Email Address</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="email" value="{{$teacher->guarantor_email}}" class="form-control" placeholder="" name="guarantorEmail">
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('guarantorEmail'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('guarantorEmail') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('guarantorOccupation') ? ' is-invalid' : '' }}">Occupation</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{$teacher->guarantor_occupation}}" class="form-control" placeholder="" name="guarantorOccupation">
				                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('guarantorOccupation'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('guarantorOccupation') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                </div><!--//toggle parent-->

                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
                                    <hr />
                                        <input type="hidden" name="userID"  value="{{ $teacher->id }}" />
			                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Update User') }}</button>
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

@section('scripts')
<script>
    $(document).ready(function(){
        $('#toggleParent:visible').hide();
        $("#showHideParentDetails" ).click(function() { 
            $("#toggleParent").toggle();
        });
    });
</script>
@endsection