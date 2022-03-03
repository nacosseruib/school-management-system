@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strtoupper(__('Set Up School Profile :: '. Auth::user()->name)) }} @endsection
@section('SchoolProfilePageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <form class="form hide-print" method="post" action="{{ route('postProfile') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('SET UP SCHOOL PROFILE') }}</h4>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                        
                            <div class="col-md-12">
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
	                    		<div class="row">
	                    			<div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolRegistrationNo') ? ' is-invalid' : '' }}">School Registration No. </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ ($schoolProfile) ? $schoolProfile->registration_no : '' }}" autofocus class="form-control" required placeholder="Registration Number" name="schoolRegistrationNo">
				                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('schoolRegistrationNo'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolRegistrationNo') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('establishmentDate') ? ' is-invalid' : '' }}">Establishment Date</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="date" value="{{ ($schoolProfile) ? $schoolProfile->establishment_date : ''}}" class="form-control" placeholder="Establishment Date" name="establishmentDate">
				                            <div class="form-control-position">
				                                <i class="fa fa-calendar"></i>
                                            </div>
                                            @if ($errors->has('establishmentDate'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('establishmentDate') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
								</div><!--//row-->
                                
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolFullName') ? ' is-invalid' : '' }}">School Full Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ ($schoolProfile) ? $schoolProfile->school_full_name : ''}}" class="form-control" required placeholder="Enter Full Name" name="schoolFullName">
				                            <div class="form-control-position">
				                                <i class="fa fa-book"></i>
                                            </div>
                                            @if ($errors->has('schoolFullName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolFullName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                    <div class="form-group col-md-3 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolShortName') ? ' is-invalid' : '' }}">Short Name <span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" maxlength="6" value="{{ ($schoolProfile) ? $schoolProfile->school_short_name : ''}}" class="form-control" required placeholder="School Short Name (No space)" name="schoolShortName">
				                            <div class="form-control-position">
				                                <i class="fa fa-book"></i>
                                            </div>
                                            @if ($errors->has('schoolShortName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolShortName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-3 mb-2">
			                            <label for="smsSenderName {{ $errors->has('smsSenderName') ? ' is-invalid' : '' }}">SMS Sender Name<span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" maxlength="11" value="{{ ($schoolProfile) ? strtoupper($schoolProfile->sms_sender_name) : ''}}" class="form-control" required placeholder="SMS Name" name="smsSenderName">
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('smsSenderName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('smsSenderName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolWebsite') ? ' is-invalid' : '' }}">School Website Name </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ ($schoolProfile) ? $schoolProfile->website : ''}}" class="form-control" placeholder="School Website" name="schoolWebsite">
				                            <div class="form-control-position">
				                                <i class="fa fa-link"></i>
                                            </div>
                                            @if ($errors->has('schoolWebsite'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolWebsite') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolEmailAddress') ? ' is-invalid' : '' }}">School Email Address</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="email" value="{{ ($schoolProfile) ? $schoolProfile->email : ''}}" class="form-control" placeholder="Email Address" name="schoolEmailAddress">
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('schoolEmailAddress'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolEmailAddress') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolPhoneNumber') ? ' is-invalid' : '' }}">School Phone Number <span class="text-danger">*</span>  </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ ($schoolProfile) ? $schoolProfile->phone_no : ''}}" class="form-control" required placeholder="School Phone Number" name="schoolPhoneNumber">
				                            <div class="form-control-position">
				                                <i class="fa fa-mobile"></i>
                                            </div>
                                            @if ($errors->has('schoolPhoneNumber'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolPhoneNumber') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolMotto') ? ' is-invalid' : '' }}">School Motto/Slogan</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" size="70" value="{{ ($schoolProfile) ? $schoolProfile->slogan : '' }}" class="form-control" placeholder="School Motto" name="schoolMotto">
				                            <div class="form-control-position">
				                                <i class="fa fa-eye"></i>
                                            </div>
                                            @if ($errors->has('schoolMotto'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolMotto') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                
                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
                                        <div class="row">
                                            <div class="col-md-3"> {!! ($schoolProfile->use_auto_reg) ? '<span style="color:green;" id="turnOnOffStatus">Enabled</span>'  : '<span style="color:red;" id="turnOnOffStatus">Disabled</span>' !!} 
                                                <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                    <input type="checkbox" name="useAutoRegistrationNumber" class="custom-control-input checkOnOff" id="useAutoRegistration" {!! ($schoolProfile->use_auto_reg) ? 'checked'  : '' !!}>
                                                    <label class="custom-control-label" for="useAutoRegistration"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <label for="timesheetinput2 {{ $errors->has('studentRegistrationIdFormat') ? ' is-invalid' : '' }}">Student Reg Number Format </label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" required name="studentRegistrationIdFormat" id="studentRegistrationIdFormat" {!! ($schoolProfile->use_auto_reg) ? ''  : 'readonly' !!}>
                                                            <option value="{{ ($schoolProfile) ? $schoolProfile->reg_formatID : '' }}">
                                                            @php $abbr =  (($schoolProfile->school_short_name) ? $schoolProfile->school_short_name : 'SMS'); @endphp
                                                                {{ str_replace('SMS', $abbr, (($schoolProfile->reg_format) ? $schoolProfile->reg_format : 'Select')) }}
                                                            </option>
                                                            @forelse($registrationIDFormat as $key => $listFormat)
                                                                <option value="{{$listFormat->reg_formatID}}">
                                                                {{ str_replace('SMS', $abbr, $listFormat->reg_format) }} 
                                                                </option>
                                                            @empty
                                                            @endforelse
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    @if ($errors->has('studentRegistrationIdFormat'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('studentRegistrationIdFormat') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div><!--//row-->
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolLogo') ? ' is-invalid' : '' }}">Attach School Logo  </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="file" class="form-control" name="schoolLogo">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('schoolLogo'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolLogo') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <br />
                                        <div align="center">
                                            <img src="{{ ($schoolProfile) ? asset(Session::get('path') . $schoolProfile->logo) : '' }}" alt=" " width="40" height="50" class="img-responsive" />
                                        </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('allowToRepulishResult') ? ' is-invalid' : '' }}">Allow result to be republished </label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="allowToRepulishResult">
                                                <option value="{{ ($schoolProfile) ? $schoolProfile->update_pulish_result : '' }}">
                                                   {{ (($schoolProfile->update_pulish_result) ? 'Republish of result is enabled' : 'Repulish of result is disabled') }}
                                                </option>
                                                <option value="1">Enable Re-publish of Result</option>
                                                <option value="0">Disable Re-publish of Result</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-file"></i>
                                            </div>
                                            @if ($errors->has('allowToRepulishResult'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('allowToRepulishResult') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="timesheetinput2 {{ $errors->has('allowToRepulishResult') ? ' is-invalid' : '' }}">School Resumption Date</label>
			                            <div class="position-relative has-icon-left">
                                            <input type="text" class="form-control" name="schoolResumptionDate" value="{{ (($schoolProfile->school_resumption_date) ? ($schoolProfile->school_resumption_date) : '') }}" />
				                            <div class="form-control-position">
				                                <i class="fa fa-calendar"></i>
                                            </div>
                                            @if ($errors->has('allowToRepulishResult'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('allowToRepulishResult') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="numberOfDaySchoolOpen {{ $errors->has('numberOfDaySchoolOpen') ? ' is-invalid' : '' }}">Number of day school open</label>
			                            <div class="position-relative has-icon-left">
                                            <input type="text" class="form-control" name="numberOfDaySchoolOpen" value="{{ ($schoolProfile) ? $schoolProfile->day_school_open : '' }}">
				                            <div class="form-control-position">
				                                <i class="fa fa-home"></i>
                                            </div>
                                            @if ($errors->has('numberOfDaySchoolOpen'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('numberOfDaySchoolOpen') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="allowEmailToBeSent {{ $errors->has('allowEmailToBeSent') ? ' is-invalid' : '' }}">Allow Email to be sent</label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="allowEmailToBeSent">
                                                <option value="0">Select</option>
                                                <option value="1" {{ ($schoolProfile) ? ($schoolProfile->send_email == 1 ? 'selected' : '') : '' }}>Send Email/SMS Notification</option>
                                                <option value="2" {{ ($schoolProfile) ? ($schoolProfile->send_email == 2 ? 'selected' : '') : '' }}>Send SMS Notification Only</option>
                                                <option value="3" {{ ($schoolProfile) ? ($schoolProfile->send_email == 3 ? 'selected' : '') : '' }}>Send Email Notification Only</option>
                                                <option value="0" {{ ($schoolProfile) ? ($schoolProfile->send_email == 0 ? 'selected' : '') : '' }}>Do Not Send Email/SMS Notification</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('allowEmailToBeSent'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('allowEmailToBeSent') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="resultCardTemplate {{ $errors->has('resultCardTemplate') ? ' is-invalid' : '' }}">Result Card Template</label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="resultCardTemplate">
                                                <option value="">Default</option>
                                                <option value="0" {{ ($schoolProfile) ? ($templateCode == 0 ? 'selected' : '') : '' }}>Default</option>
                                                <option value="1" {{ ($schoolProfile) ? ($templateCode == 1 ? 'selected' : '') : '' }}>Template 1</option>
                                                <option value="2" {{ ($schoolProfile) ? ($templateCode == 2 ? 'selected' : '') : '' }}>Template 2</option>
                                                <option value="3" {{ ($schoolProfile) ? ($templateCode == 3 ? 'selected' : '') : '' }}>Template 3</option>
                                                <option value="4" {{ ($schoolProfile) ? ($templateCode == 4 ? 'selected' : '') : '' }}>Template 4</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-home"></i>
                                            </div>
                                            @if ($errors->has('resultCardTemplate'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('resultCardTemplate') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="resultCardWatermark {{ $errors->has('resultCardWatermark') ? ' is-invalid' : '' }}">Result Watermark</label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="resultCardWatermark">
                                                <option value="">Select Watermark</option>
                                                <option value="0" {{ ($schoolProfile) ? ($watermarkCode == 0 ? 'selected' : '') : '' }}>No Watermark</option>
                                                <option value="1" {{ ($schoolProfile) ? ($watermarkCode == 1 ? 'selected' : '') : '' }}>Watermark with School Logo </option>
                                                <option value="2" {{ ($schoolProfile) ? ($watermarkCode == 2 ? 'selected' : '') : '' }}>Watermark with Original</option>
                                                <option value="3" {{ ($schoolProfile) ? ($watermarkCode == 3 ? 'selected' : '') : '' }}>Watermark with Not Official</option>
                                                <option value="4" {{ ($schoolProfile) ? ($watermarkCode == 4 ? 'selected' : '') : '' }}>Watermark with School Logo & Original</option>
                                                <option value="5" {{ ($schoolProfile) ? ($watermarkCode == 5 ? 'selected': '') : '' }}>Watermark with School Logo & Not Official</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-envelope"></i>
                                            </div>
                                            @if ($errors->has('resultCardWatermark'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('resultCardWatermark') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->
                                

                                <div class="row">
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('schoolAddress') ? ' is-invalid' : '' }}">School Address</label>
			                            <div class="position-relative has-icon-left">
			                            	<textarea rows="4" class="form-control" placeholder="School Address" name="schoolAddress">{{ ($schoolProfile) ? $schoolProfile->address : '' }}</textarea>
				                            <div class="form-control-position">
				                                <i class="fa fa-map"></i>
                                            </div>
                                            @if ($errors->has('schoolAddress'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolAddress') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="signature {{ $errors->has('signature') ? ' is-invalid' : '' }}">Attach Principal Signature </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="file" class="form-control" name="signature">
				                            <div class="form-control-position">
				                                <i class="fa fa-user"></i>
                                            </div>
                                            @if ($errors->has('signature'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('signature') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-2 mb-2">
                                        <br />
                                        <div align="center">
                                            <img src="{{ ($schoolProfile) ? asset(Session::get('path') . $schoolProfile->signature) : '' }}" alt=" " width="70" height="35" class="img-responsive" />
                                        </div>
                                    </div>
                                </div><!--//row-->
                                
                                <div class="row">
                                     <div class="form-group col-md-6 mb-2">
                                        <label for="showFeeOnReport {{ $errors->has('showFeeOnReport') ? ' is-invalid' : '' }}">Show Fee BreakDown on Reports</label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="showFeeOnReport">
                                                <option value="">Select</option>
                                                <option value="0" {{ ($schoolProfile) ? ($schoolProfile->show_fee_breakdown == 0 ? 'selected' : '') : '' }}>Do Not Show Fees BreakDown on Report</option>
                                                <option value="1" {{ ($schoolProfile) ? ($schoolProfile->show_fee_breakdown == 1 ? 'selected' : '') : '' }}>Show Fees BreakDown on Admission Letter Only</option>
                                                <option value="2" {{ ($schoolProfile) ? ($schoolProfile->show_fee_breakdown == 2 ? 'selected' : '') : '' }}>Show Fees BreakDown on Report Card Only</option>
                                                <option value="3" {{ ($schoolProfile) ? ($schoolProfile->show_fee_breakdown == 3 ? 'selected' : '') : '' }}>Show Fees BreakDown on All Reports</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-money"></i>
                                            </div>
                                            @if ($errors->has('showFeeOnReport'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('showFeeOnReport') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                       <label for="">&nbsp;</label>
                                       
                                    </div>
                                </div><!--//row-->

                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
                                    <hr />
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Update Profile') }}</button>
                                    </div>
								</div><!--//row-->

                            </div><!--//col-8-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('styles')
<style>
    .custom-control-label::before, 
    .custom-control-label::after {
    top: .8rem;
    width: 2.25rem;
    height: 2.25rem;
    }
</style>
@endsection

@section('scripts')
  <script>
  //Enable/Disable Registration
  $(document).ready(function() {
      $(".checkOnOff").click(function() {
          var checkStatus = 0; 
          if ($('.checkOnOff').is(":checked"))
          {
            checkStatus = 1; //'ON';
            $(".checkOnOff").prop("checked", true);
            $('#turnOnOffStatus').html('Enabled').css('color','green');
            $('#studentRegistrationIdFormat').attr('readonly', false);
          }else{
            checkStatus = 0; //'OFF';
            $(".checkOnOff").prop("checked", false);
            $('#turnOnOffStatus').html('Disabled').css('color','red');
            $('#studentRegistrationIdFormat').attr('readonly', true);
          } 
          // 
        $.ajax({
            url: '{{url("/")}}' + '/turn-on-off-auto-registration',
            type: "post",
            data: {'enableDisableID': checkStatus, '_token': $('input[name=_token]').val()},
             success: function(data){
                if(data.code == 1){
                    $('#turnOnOffStatus').html('Enabled').css('color','green');
                    $('#studentRegistrationIdFormat').attr('readonly', false);
                }else{
                    $('#turnOnOffStatus').html('Disabled').css('color','red');
                    $('#studentRegistrationIdFormat').attr('readonly', true);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Internal/Internet error occurred! Looks like your session has expired or you are not connected to the internet.');
            }
        });
      });//end function
    });//end ready
  //
  </script>
@endsection