@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Send  SMS')) }} @endsection
@section('sendSMSPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  id="sendSMSForm" class="form" method="post" action="{{route('sendSMS')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('SEND SMS') }}</h4>
                            <hr />

                            <div class="row offset-md-0">
                                <div class="col-md-5 mt-2">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <label> Sender (From)</label>
                                                <input type="text"  readonly value="{{ (isset($schoolPhoneNo) ? $schoolPhoneNo : 'SchlEportal' )}}" class="form-control" required name="sender" placeholder="Enter Sender's Number">
                                                @if ($errors->has('sender'))
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('sender') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div><!--//row-->
                                        <div class="row">
                                            <div class="col-md-12 mt-2"> 
                                                <label> Message</label>  <i><span id="getCountChar" class="text-right pull-right"></span></i> 
                                                <textarea id="textareaCharacter" maxlength ="160" class="form-control font-weight-bold" name="message" required placeholder="Enter Message" style="min-height:180px;">{{old('message')}}</textarea>
                                                @if ($errors->has('message'))
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('message') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-12 mt-2 bg-light">
                                                <label class="text-info">SMS Sample Template</label>
                                                <select class="form-control mb-2" name="smsTemplate" id="smsTemplate">
                                                    <option value="" selected>Select any sample template</option>
                                                    @if($smsTemplate)
                                                        @forelse($smsTemplate as $keyTemp=>$template)
                                                            <option value="{!! $template->message !!}">{{ '('. (1+$keyTemp) .'). '. ucwords(strtolower($template->sms_title)) }}</option>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                                @if ($errors->has('smsTemplate'))
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('smsTemplate') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div><!--//row-->
                                </div>
                                <div class="col-md-7 mt-2">
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <label> Enter Recipient's Number [<small><small class="text-lowercase text-danger">Use comma '<strong>,</strong>' to separate numbers</small></small>] </label>
                                                <input type="text"  value="{{old('receiver')}}" class="form-control" required name="receiver" placeholder="Enter Recipient's Number">
                                                @if ($errors->has('receiver'))
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('receiver') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div><!--//row-->
                                        <div class="row">
                                            <div class="col-md-12 mt-2">
                                                <label> Select Recipient</label>
                                                <select multiple class="form-control" required name="recipient[]" style="min-height:250px;">
                                                    <option value="">None</option>
                                                    @if($parentphoneNo)
                                                        @forelse($parentphoneNo as $key=>$value)
                                                            <option value="{{$value->parent_telephone}}">{{ '('. (1+$key) .'). '.  $value->student_lastname.' '. $value->student_firstname .' - '.$value->student_regID .' : [ '. ( $value->parent_telephone ? $value->parent_telephone : ' No Phone Number ') .' ]' }}</option>
                                                        @empty
                                                        @endforelse
                                                    @endif
                                                </select>
                                                @if ($errors->has('recipient'))
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('recipient') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div><!--//row-->
                                </div>
                            </div><!--//row-->
                        <hr />
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top">
                                        <div class="buttons-group text-center">
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-backdrop="false" data-target="#sendSMSModal">
                                                <i class="fa fa-check-square-o"></i> {{ __('Send SMS') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>

                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            </form>
    
                                <!-- Confirm send SMS Modal -->
                                <div class="modal fade text-left d-print-none" id="sendSMSModal" tabindex="-1" role="dialog" aria-labelledby="sendSMS" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                            <h5 class="modal-title" id="sendSMS"><i class="fa fa-send"></i> {{ __('Confirm Sending SMS')}}  </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <h5 class="text-center"> {{ __('Are you sure you want to send this sms to the recipient(s)')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('Your message will be sent to the selected recipient(s)')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <button type="button" id="sendNow" class="btn btn-outline-success">{{ __('Send Now') }}</button>
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
        
        //SEND SMS
        $('#sendNow').click(function(){
            $('#sendSMSForm').submit();
        });
        
        //COUNT CHARACTERS  
        $('#getCountChar').html( '(160 Chars. Left)' ).css('color', 'grey');
        $('#textareaCharacter').keyup(function () {
          var max = 160;
          var len = $(this).val().length;
          if (len >= max) {
                $('#getCountChar').html(' Max. Chars. Limit Reached!').css('color', 'red');
          }else if(len == max) {
                $('#getCountChar').html( '(160 Chars. Left)' ).css('color', 'grey');
          } else {
                var char = max - len;
                var textChar
                if(char > 1)
                {
                    textChar = " Chars. Left";
                }else{
                    textChar = " Char. Left";
                }
                if(char > 9)
                {
                     $('#getCountChar').html(char +  textChar).css('color', 'green');
                }else{
                    $('#getCountChar').html(char +  textChar).css('color', 'red');
                }
          }
        });
        
        //Get Template Message
        $('#smsTemplate').change(function () 
        {  
            var message = $('#smsTemplate').val();
            if(message != '')
            {   
                if(confirm("NOTE: all the messages you have typed will be clear! Do you want to continue?"))
                {
                    $('#textareaCharacter').val('');
                    $('#textareaCharacter').val(message);
                }
            }else{
                alert('No Message Selected !');
            }
             
        });
        
        
    });//end document
</script>
@endsection