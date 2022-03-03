@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Generate Result PIN ::'. Auth::user()->name)) }} @endsection
@section('generatePINPageActive') active @endsection
@section('content')

<div class="main-content">
    <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
        <form id="submitGeneratePINForm" class="form" method="post" action="{{route('postProcessResultPIN')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('GENERATE RESULT PIN CHECKER') }}</h4>
                            <hr />
                            <div class="alert alert-secondary" role="alert">
                                <strong><i class="fa fa-key"></i> {{ __('Manage and Generate All PINs For Checking Result For Each School Term') }}</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label> {{ __('Current Session') }} <b><span class="text-danger">*</span></b></label>
                                    <input type="text" class="form-control" name="schoolSession" required readonly value="{{ ($getSession) ? $getSession->session_code : 'Please, Set School Session' }}">
                                </div>
                                <div class="col-md-4">
                                    <label> {{ __('Current Term') }} <b><span class="text-danger">*</span></b></label>
                                    <select class="form-control" required readonly name="schoolTerm" id="schoolTerm">
                                        <option value="{{ ($getSession) ? $getSession->termID : '' }}"> {{ ($getSession) ? $getSession->term_name : '' }}</option>
                                        <!--@forelse($allTerm as $listTerm)
                                            <option value="{{ $listTerm->termID }}">{{ $listTerm->term_name }}</option>
                                        @empty
                                        @endforelse-->
                                    </select>
                                    @if ($errors->has('termName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('termName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label> {{ __('Select PIN Type') }} <b><span class="text-danger">*</span></b></label>
                                    <select class="form-control" required name="pinType" id="pinType">
                                        <option value=""> Select </option>
                                        <option value="PIN_PER_USER_TERM" {{old('pinType') == 'PIN_PER_USER_TERM' ? 'selected' : ''}}> PIN Per User/Parent Per Term (Recommended) </option>
                                        <option value="PIN_ANY_USER_TERM" {{old('pinType') == 'PIN_ANY_USER_TERM' ? 'selected' : ''}}> PIN Per any User/Parent Per Term</option>
                                        <option value="PIN_PER_USER_SESSION" {{old('pinType') == 'PIN_PER_USER_SESSION' ? 'selected' : ''}}> PIN Per User/Parent Per Session (3 Terms) </option>
                                        <option value="PIN_PER_USER_ONE_TIME" {{old('pinType') == 'PIN_PER_USER_ONE_TIME' ? 'selected' : ''}}> One Time PIN Usage Per User/Parent </option>
                                        <option value="PIN_NO_LIMIT_A_YEAR" {{old('pinType') == 'PIN_NO_LIMIT_A_YEAR' ? 'selected' : ''}}> No Usage Limit PIN (Max 1 Year) </option>
                                    </select>
                                    @if ($errors->has('termName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('termName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row">
                                <div class="col-md-4 mt-2">
                                    <label> {{ __('Select Class') }} <b><span class="text-danger">*</span></b> </label>
                                    <select class="form-control" required name="className">
                                        <option value="" selected>Select</option>
                                        <option value="{{strtoupper('ALL')}}" {{old('className') == strtoupper('ALL') ? 'selected' : ''}}>For Entire School</option>
                                        @forelse($allClass as $class)
                                            <option value="{{ $class->classID }}" {{old('className') == $class->classID ? 'selected' : ''}}>{{ __($class->class_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('className'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('className') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label> {{ __('Send PIN Via Email/SMS') }} <b><span class="text-danger">*</span></b> </label>
                                    <select class="form-control" required name="sendEmail">
                                        <option value="" selected>Select</option>
                                        <option value="1" {{old('sendEmail') == 1 ? 'selected' : ''}}>Yes. Send Pin via email only</option>
                                        <option value="2" {{old('sendEmail') == 2 ? 'selected' : ''}}>Yes. Send Pin via SMS only</option>
                                        <option value="3" {{old('sendEmail') == 3 ? 'selected' : ''}}>Yes. Send Pin via email and SMS only</option>
                                        <option value="0" {{old('sendEmail') == 0 ? 'selected' : ''}}>No. Do not send Pin via email/SMS</option>
                                        
                                    </select>
                                    @if ($errors->has('sendEmail'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sendEmail') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 mt-2">
                                    
                                </div>
                            </div><!--//row-->
                            
                        </div>
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group float-right">
                                            <button type="button" data-toggle="modal" data-backdrop="false" data-target="#confirmPINParameters" class="btn btn-sm btn-success">
                                                <i class="fa fa-check-square-o"></i> {{ __('Save and Continue') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
        </form>
                
            <!--Confirm Publishing  Modal -->
            <div class="modal fade text-left" id="confirmPINParameters" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success white">
                        <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-key"></i> {{ __('Please confirm your parameters !')}}  </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <h5 class="text-success"><i class="fa fa-arrow-right"></i> {{ __("Generating PIN for each class/entire school will enables user/parent to check student result.")}} </h5>
                        <p class="text-center text-danger">
                            {{ __(' Are you sure you want to continue with this operation ? ')}}
                        </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Edit/Cancel</button>
                            <button type="button" id="generatePIN" class="btn btn-outline-success">Publish and Generate PIN</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end Modal-->
        
    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //start process PIN Generation
        $("#generatePIN" ).click(function() { 
            $("#submitGeneratePINForm").submit();
        }); //end function
        
    });//end document
</script>
@endsection