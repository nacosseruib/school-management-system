@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Create School Session ::'. Auth::user()->name)) }} @endsection
@section('schoolSessionPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  id="submitSchoolSessionForm" class="form" method="post" action="{{route('postSchoolSession')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CREATE SCHOOL SESSION') }}</h4>
                            <hr />
                            <div class="alert alert-secondary text-info text" role="alert">
                                <strong>@if($currentSession) {{ ($currentSession) ? ('Current Session: '. $currentSession->session_code .' - '. $currentSession->term_name) : '' }} @endif</strong>
                            </div>
                            <div class="row offset-md-1">
                                <div class="col-md-5">
                                    <label> {{ __('Select School Session') }} </label>
                                    <select class="form-control" required name="schoolSession" id="schoolSession">
                                        <option value="{{ ($currentSession) ? $currentSession->session_code : '' }}" selected> {{ ((!empty($currentSession)) ? $currentSession->session_code : 'Select') }} </option>
                                        @forelse($getSessionHistory as $sessionHistory)
                                            <option value="{{ $sessionHistory->session_code }}">
                                                {{ $sessionHistory->session_code .' - '. $sessionHistory->term_name . (($sessionHistory->activeSession == 1) ? ' - Current Session' : '')}}
                                            </option>
                                        @empty
                                        @endforelse
                                        <option value="{{ (date('Y') -2) .'/'. (date('Y') -1) }}">{{ (date('Y') -2) .'/'. (date('Y') -1) }}</option>
                                        <option value="{{ (date('Y') -1) .'/'. (date('Y')) }}">{{ (date('Y') -1) .'/'. (date('Y')) }}</option>
                                        <option value="{{ (date('Y') +1) .'/'. (date('Y') + 2) }}">{{ (date('Y') +1) .'/'. (date('Y') + 2) }}</option>
                                        <option value="{{ date('Y') .'/'. (date('Y') + 1) }}">{{ date('Y') .'/'. (date('Y') + 1) }}</option>
                                    </select>
                                    @if ($errors->has('schoolSession'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('schoolSession') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <label> Enter Session Description </label>
                                    <input type="text" value="{{ (($currentSession) ? $currentSession->session_name : '') }}" required class="form-control" name="sessionDescription" id="sessionDescription" placeholder="Session Description will be shown to all">
                                    @if ($errors->has('sessionDescription'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sessionDescription') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                        <label> {{ __('Enable/Disable Result Submission') }}</label>
                                        <select class="form-control" required name="allowResultComputation" id="allowResultComputation">
                <option value="{{($currentSession) ? $currentSession->allow_result_computation : ''}}" selected> 
        {{ (($currentSession) ? ($currentSession->allow_result_computation) ? "Results submission are allowed" : "Results submission are not allowed" : "Results submission are not allowed ") }}
                                            </option>
                <option value="1">Results submission are allowed </option>
                <option value="0">Results submission are not allowed </option>
                                        </select>
                                        @if ($errors->has('allowResultComputation'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('allowResultComputation') }}</strong>
                                            </span>
                                        @endif
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Term') }} </label>
                                    <select class="form-control" required name="termName" id="termName">
                                        <option value="{{($currentSession) ? $currentSession->termID : ''}}" selected> 
    {{($currentSession) ? (($currentSession->termID) ? $currentSession->term_name : 'Select') : 'Select' }}  </option>
                                        @forelse($allTerm as $listTerm)
                                            <option value="{{ $listTerm->termID }}">{{ $listTerm->term_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('termName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('termName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                        </div> 

                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group float-right">
                                            <button  id="checkFields" type="button" data-toggle="modal" data-backdrop="false" data-target="#confirmNewSession" class="btn btn-sm btn-success">
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
                
        <!--Confirm operation  Modal -->
        <div class="modal fade text-left" id="confirmNewSession" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Set New School Session')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5 class="text-primary"><i class="fa fa-arrow-right"></i> {{ __('Setting Session enables Staff and other school activities to kick start.')}} </h5>
                      <p class="text-center text-warning">
                        {{ __('Are you sure you want to continue with this operation ?')}}
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Edit/Cancel</button>
                        <button type="submit" id="submitForm" class="btn btn-outline-success">Save and Set Now</button>
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

        //check session name
        $("#checkFields").click(function() {
            if(($("#schoolSession").val()) == ''){
                alert('You have to select school session !');
                $("#schoolSession").focus();
                return false;
            }
            //check session description
            if($("#sessionDescription").val() == ''){
                alert('Enter school session short description. This will be shown to all !');
                $("#sessionDescription").focus();
                return false;
            }
            //check term 
            if($("#termName").val() == ''){
                alert('Select school term for this session !');
                $("#termName").focus();
                return false;
            }
        }); 

        $("#submitForm").click(function() {
            $('#submitSchoolSessionForm').submit();
        }); 
    });//end document
</script>
@endsection