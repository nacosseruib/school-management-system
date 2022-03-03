@extends('layouts.authLayout')
@section('pageHeaderTitle') {{ strtoupper(__('Create Teacher ::'. Auth::user()->name)) }} @endsection
@section('teacherPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <form class="form hide-print" method="post" action="{{ route('storeTeacher') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CREATE NEW TEACHER/USER') }}</h4>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />

                            <div class="col-md-10 offset-md-1">
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('surname') ? ' is-invalid' : '' }}">Surname/Last Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input autofocus type="text" value="{{old('surname')}}" class="form-control" required placeholder="Surname/Last Name" name="surname">
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
			                            <label for="timesheetinput2 {{ $errors->has('userRegistrationId') ? ' is-invalid' : '' }}">User RegID (Auto Generate) <span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" readonly value="{{ $newTeacherRegNo }}" class="form-control" required placeholder="Registration ID" name="userRegistrationId">
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
			                            	<input type="date" value="{{old('admittedDate')}}" class="form-control" required placeholder="" name="admittedDate">
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
                                                <option value="">Select</option>
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
                                        <input type="text" value="{{old('phoneNumber')}}" class="form-control" placeholder="Phone Number (Optional)" name="phoneNumber">
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
			                            	<input type="text" value="{{old('address')}}" class="form-control" placeholder="Address (Optional)" name="address">
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
                                                        <option value="">Select</option>
                                                        <option>Supervisor</option>
                                                        <option>Principal</option>
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
                                                <label for="timesheetinput2 {{ $errors->has('userType') ? ' is-invalid' : '' }}">User's Type <span class="text-danger">*</span></label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" required name="userType" id="userType">
                                                        <option value=""> Select </option>
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
			                            <label for="timesheetinput2 {{ $errors->has('teacherClass') ? ' is-invalid' : '' }}">Select Teacher's Class(es) <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select  multiple required class="form-control" name="teacherClass[]" style="min-height:135px;">
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
			                            	<input type="email" required value="{{old('email')}}" class="form-control" placeholder="Email Address" name="email">
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
			                            <label for="timesheetinput2 {{ $errors->has('password') ? ' is-invalid' : '' }}">Password <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="password" required class="form-control" placeholder="Password" name="password">
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
			                            <label for="timesheetinput2 {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">Confirm Password <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="password" required class="form-control" placeholder="Password" name="password_confirmation">
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
			                            	<input type="text" value="{{old('guarantorFirstName')}}" class="form-control" placeholder="" name="guarantorFirstName">
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
			                            	<input type="text" value="{{old('guarantorLastName')}}" class="form-control" placeholder="" name="guarantorLastName">
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
			                            	<input type="text" value="{{old('guarantorTelephone')}}" class="form-control" placeholder="" name="guarantorTelephone">
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
			                            	<input type="text" value="{{old('guarantorAddress')}}" class="form-control" placeholder="" name="guarantorAddress">
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
			                            	<input type="email" value="{{old('guarantorEmail')}}" class="form-control" placeholder="" name="guarantorEmail">
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
			                            	<input type="text" value="{{old('guarantorOccupation')}}" class="form-control" placeholder="" name="guarantorOccupation">
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
	                    			<div class="form-group col-12 mb-2">
                                    <hr />
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Create New User') }}</button>
                                    </div>
								</div><!--//row-->
                            </div><!--//col-8-->
                        </form>

                        <div align="center" class="col-md-12 offset-md-0">
                            <table class="table table-hover table-stripped table-responsive table-condensed">
                            <thead>
                                <tr style="background:#d9d9d9; font-size:12px;">
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('User RegID') }}</th>
                                    <th>{{ __('Surname') }}</th>
                                    <th>{{ __('Other Names') }}</th>
                                    <th>{{ __('Post') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Admitted Date') }}</th>
                                    <th>{{ __('Telephone') }}</th>
                                    <th>{{ __('Last Update') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody style="font-size:12px;">
                                @forelse($allUser as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->userRegistrationId) }}</th>
                                    <th>{{ __($list->name) }}</th>
                                    <th>{{ __($list->other_name ) }}</th>
                                    <th>{{ __($list->designation) }}</th>
                                    <th>{{ __($list->gender) }}</th>
                                    <th>{{ __($list->admitted_date) }}</th>
                                    <th>{{ __($list->telephone) }}</th>
                                    <th>{{ __($list->lastUpdate) }}</th>
                                    <th><a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="false" data-target="#deleteStudent{{$list->id}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade text-left" id="deleteStudent{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete User')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->name.' '. $list->other_name .' - '. $list->userRegistrationId) }} ! </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __("This record will be moved to user's recycle bin !")}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeTeacher', [$list->id])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Modal-->

                                @empty
                                    <tr><td colspan="11" class="text-danger">{{ __('No record found!') }}</td></tr>
                                @endforelse
                            </tbody>
                            </table>
                            <div class="row">
                              <div align="right" class="col-xs-12 col-sm-12">
                                  Showing {{($allUser->currentpage()-1)*$allUser->perpage()+1}}
                                  to {{$allUser->currentpage()*$allUser->perpage()}}
                                  of  {{$allUser->total()}} entries
                                  <br />
                                  <div class="hidden-print">{{ $allUser->links() }}</div>
                              </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->
@endsection

@section('scripts')
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script>
$(document).ready(function(){
    $('#toggleParent:visible').hide();
    $("#showHideParentDetails" ).click(function() {
        $("#toggleParent").toggle();
    });
});
</script>
@endsection
