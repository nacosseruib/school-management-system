@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('PIN MANAGEMENT PANEL :: '. Auth::user()->name)) }} @endsection
@section('viewGeneratePINPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  id="submitGradePointForm" class="form d-print-none" method="post" action="{{route('postViewResultPIN')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('PIN MANAGEMENT PANEL') }}</h4>
                            <hr />
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label> {{ __('Current Session') }} </label>
                                    <select class="form-control" name="schoolSession" id="schoolSession">
                                        <option value="" selected> Select </option>
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
                                </div>
                                <div class="col-md-4">
                                    <label> {{ __('Select Term') }} </label>
                                    <select class="form-control" name="termName" id="termName">
                                        <option value=""> Select </option>
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
                                <div class="col-md-4">
                                    <label> {{ __('Select Class') }} </label>
                                    <select class="form-control" name="className" id="className">
                                        <option value="" selected> Select </option>
                                        <option value="All"> All Classes(Entire School) </option>
                                        @forelse($allClass as $listClass)
                                            <option value="{{ $listClass->classID }}">{{ $listClass->class_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('className'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('className') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                        </div>
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group float-right">
                                            <input type="hidden" name="gradeID" value="{{ ((isset($grade)) ? $grade->gradeID : '') }}" />
                                            @if(isset($grade))
                                            <a href="{{ route('cancelEditGrade') }}"  class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Cancel Edit
                                            </a>
                                            @endif
                                            <button  id="checkFields" type="submit" type="hidden" data-toggle="modal" data-backdrop="false" data-target="#confirmNewGradePoint" class="btn btn-sm btn-success">
                                                <i class="fa fa-search"></i> {{ __('Search') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            </form>
            
            <div class="card row">
            <div align="center" class="card-header col-md-12">
            <h6 class="card-title" id="from-actions-multiple">{{ __('RESULT PIN DETAILS FOR ') . ( Session::get('schoolSession') ?  Session::get('schoolSession') .' - ' . Session::get('termName') .' - '. Session::get('className')  : 'Current Session & Term') }}</h6>
            <br />
                <table class="table table-hover table-stripped table-responsive table-condensed"> 
                    <thead>
                        <tr style="font-size:13px; background:#d9d9d9">
                            <th>{{ __('S/N') }}</th>
                            <th>{{ __('PIN') }}</th>
                            <th>{{ __('Reg. No.') }}</th>
                            <th>{{ __('Surname') }}</th>
                            <th>{{ __('Other Name') }}</th>
                            <th>{{ __('Class') }}</th>
                            <th>{{ __('Term') }}</th>
                            <th>{{ __('Session') }}</th>
                            <!--<th>{{ __('Expire') }}</th>-->
                            <th>{{ __('Generated') }}</th>
                            <th>{{ __('Used') }}</th>
                            <th class="d-print-none" colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody style="font-size:13px;">
                        @forelse($allPINCurrentSession as $key=>$list)
                        <tr>
                            <td>{{ 1+$key ++ }}</td>
                            <td class="text-success"><big>{{ ($allPINCurrentSession) ?  ($list->pin) : ''}}</big></td>
                            <td>{{ ($allPINCurrentSession) ?  ($list->student_regID) : ''}}</td>
                            <td>{{ ($allPINCurrentSession) ?  ($list->student_lastname) : ''}}</td>
                            <td>{{ ($allPINCurrentSession) ?  ($list->student_firstname) : ''}}</td>
                            <td>{{ ($allPINCurrentSession) ?  ($list->class_name) : ''}}</td>
                            <td>{{ ($allPINCurrentSession) ?  ($list->school_term_name) : ''}}</td>
                            <td>{{ ($allPINCurrentSession) ?  ($list->school_session) : ''}}</td>
                            <!--<td>{{ ($allPINCurrentSession) ?  ($list->pin_expire) : ''}}</td>-->
                            <td>{{ ($allPINCurrentSession) ?  ($list->pinCreated) : ''}}</td>
                            <td class="text-success"><b>{{ ($allPINCurrentSession) ?  ($list->user_no_of_time_use) : '0'}}</b></td>
                            <td class="d-print-none">
                                <small>{!! ($allPINCurrentSession and $list->is_pin_active == 1) ? '<span class="text-success">Active</span>' : '<span class="text-danger">'. ($list->has_expire == 1 ? 'Expired' : 'Disabled') .'</span>' !!}</small>
                            </td>
                            <td class="d-print-none">
                                <button class="btn btn-secondary btn-sm" data-toggle="modal" data-backdrop="false" data-target="#enableDisable{{$list->pinID}}">
                                    <i class="fa fa-gear"></i>
                                </button>
                            </td>
                        </tr>

                            <!-- Enable Disable Modal -->
                                <div class="modal fade text-left d-print-none" id="enableDisable{{$list->pinID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-lock"></i> {{ __('Enable/Disable PIN')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-left">  {{ __('Enable/Disable') }} </div>
                                                <div class="mt-2 mb-2">
                                                    <select required class="form-control" name="enableOrDisable" id="changeStatus{{$list->pinID}}">
                                                        <option value=""> Select </option>
                                                        <option value="0">Disable</option>
                                                        <option value="1">Enable</option>
                                                    </select>
                                                </div>
                                                <h5><i class="fa fa-arrow-lock"></i> {{ __('Are you sure you want to perform this operation ?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('This PIN will be enabled/disabled based on your selected option !')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <span id="pleaseWait{{$list->pinID}}"></span>
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <button type="button" id="{{$list->pinID}}" class="updateStatus btn btn-outline-danger">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end Modal-->

                        @empty
                            <tr><td colspan="12" class="text-danger">{{ __('No record found!') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                     
                     <hr />
                    <div class="row">
                    <div align="right" class="col-md-12">
                        Showing {{($allPINCurrentSession->currentpage()-1)*$allPINCurrentSession->perpage()+1}}
                                to {{$allPINCurrentSession->currentpage()*$allPINCurrentSession->perpage()}}
                                of  {{$allPINCurrentSession->total()}} entries
                        </div>
                        <div class="d-print-none">{{ $allPINCurrentSession->links() }}</div>
                    </div>
                    <br />

                </div>
            </div>
                
       
    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script> 
    $(document).ready(function(){

        $(".updateStatus").click(function() { 
            var pinID = this.id;
            $('#pleaseWait' + pinID).html('').hide();
            var statusID = $("#changeStatus" + pinID).val();

            if(statusID < 0 || pinID == '' || statusID ==''){
                alert('Sorry, you have not selected any status!');
                return false;
            }
            
            $('#pleaseWait' + pinID).html('Please wait, processing...').css('color','red').show();

            $.ajax({
                url: "{{url('/')}}" + '/enable-disable-PIN-Json',
                type: "post",
                data: {'pinID': pinID, 'operation': statusID, '_token': $('input[name=_token]').val()},
                success: function(data){
                    $('#pleaseWait' + pinID).html(data).css('color','green').fadeIn(1000);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('An error occurred! Looks like your session has expired or you are not connected to the internet.');
                    $('#pleaseWait' + pinID).html('').hide();
                }
            });
        });
        
    });//end document
</script>
@endsection