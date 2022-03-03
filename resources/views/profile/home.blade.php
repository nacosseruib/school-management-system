@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Update Profile')) }} @endsection
@section('profilePageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  class="form" method="post" action="{{route('postUpdateProfile')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('UPDATE PROFILE') }}</h4>
                            <hr />
                            
                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> Surname</label>
                                    <input type="text"  value="{{ ($editUser ? $editUser->name : old('surname') )}}" class="form-control" required name="surname" placeholder="Surname">
                                    @if ($errors->has('surname'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('surname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> Other Name</label>
                                    <input type="text"  value="{{ ($editUser ? $editUser->other_name : old('otherName') )}}" class="form-control" name="otherName" placeholder="Other Name">
                                    @if ($errors->has('otherName'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('otherName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> Email Address</label>
                                    <input type="emailAddress"  value="{{ ($editUser ? $editUser->email : old('emailAddress') )}}" class="form-control" required name="emailAddress" placeholder="Email Address">
                                    @if ($errors->has('emailAddress'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('emailAddress') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> Phone Number </label>
                                    <input type="text"  value="{{ ($editUser ? $editUser->telephone : old('phoneNumber') )}}" class="form-control" name="phoneNumber" placeholder="Phone Number">
                                    @if ($errors->has('phoneNumber'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('phoneNumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row offset-md-1">
                                <div class="col-md-10 mt-2">
                                    <label> Home Address</label>
                                    <textarea class="form-control" name="homeAddress" placeholder="Home Address">{{ ($editUser ? $editUser->address : old('homeAddress') )}}</textarea>
                                    @if ($errors->has('homeAddress'))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('homeAddress') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row offset-md-1">
                                <div class="form-group col-md-5 mt-2">
			                            <label for="timesheetinput2 {{ $errors->has('password') ? ' is-invalid' : '' }}">Password <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="password" class="form-control" placeholder="Password" name="password">
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
                                    <div class="form-group col-md-5 mt-2">
			                            <label for="timesheetinput2 {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}">Confirm Password <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="password" class="form-control" placeholder="Password" name="password_confirmation">
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

                        </div> 

                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top">
                                        <div class="buttons-group text-center">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa fa-check-square-o"></i> {{ __('Update') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            </form>
                
    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){

    });//end document
</script>
@endsection