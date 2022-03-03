@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Publish Result ::'. Auth::user()->name)) }} @endsection
@section('publisResultPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form id="submitPublishForm" class="form" method="post" action="{{route('postPublishResult')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('PUBLISH RESULT') }}</h4>
                            <hr />
                            <div class="alert alert-secondary" role="alert">
                                <strong>{{ __('Publishing results make it available to the public.') }}</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label> {{ __('Current Session') }} </label>
                                    <input type="text" class="form-control" name="schoolSession" required readonly value="{{ ($getSession) ? $getSession->session_code : 'Please, Set School Session' }}">
                                </div>
                                <div class="col-md-4">
                                    <label> User's Name </label>
                                    <div class="form-control" name="teacherName" readonly>
                                        @if(Auth::check()) {{ Auth::User()->name}} @endif
                                    </div>
                                        
                                </div>
                                <div class="col-md-4">
                                    <label> {{ __('Select Term') }} </label>
                                    <select class="form-control" required readonly name="termName" id="termName">
                                        <option value="{{ ($getSession) ? $getSession->termID : '' }}"> {{ ($getSession) ? $getSession->term_name : '' }}</option>
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

                            <div class="row">
                                <div class="col-md-4 mt-2">
                                    <label> {{ __('Select Class') }} </label>
                                    <select class="form-control" required name="className" id="getClassID">
                                        <option value="" selected> Select Class </option>
                                        <option value="All">All Classes</option>
                                        @forelse($allClass as $class)
                                            <option value="{{ $class->classID }}">{{ __($class->class_name) }}</option>
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
                                    <label> {{ __('Select Subject') }} </label>
                                    <select class="form-control" required name="subjectName" id="getClassSubject">
                                        <option value="" selected> Select Subject </option>
                                        <option value="All">For all subjects in all classes</option>
                                        <!--@forelse($allSubject as $subject)
                                            <option value="{{ $subject->subjectID }}">{{ __($subject->subject_name) }}</option>
                                        @empty
                                        @endforelse-->
                                        <option value="">None</option>
                                    </select>
                                    @if ($errors->has('subjectName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subjectName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label> {{ __('Select Score Type') }} </label>
                                    <select required class="form-control" name="scoreTypeName" id="scoreTypeName"> 
                                        <option value="" selected>Select</option>
                                        <option value="All">All Tests(CA1, CA2) & Exam</option>
                                        @forelse($allScoreType as $scoreType)
                                            <option value="{{ $scoreType->scoretypeID}}">{{ $scoreType->score_type }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('scoreTypeName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('scoreTypeName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                            
                        </div>
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group float-right">
                                            <button type="button" data-toggle="modal" data-backdrop="false" data-target="#confirmPublish" class="btn btn-sm btn-success">
                                                <i class="fa fa-check-square-o"></i> {{ __('Save and Publish') }}
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
        <div class="modal fade text-left" id="confirmPublish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Confirm Result Publishing')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5 class="text-warning"><i class="fa fa-arrow-right"></i> {{ __('Publishing will only be done for the parameters selected and affected result(s) will not be editable again but visible to public.')}} </h5>
                      <p class="text-center text-success">
                        {{ __('Are you sure you want to continue with result publishing ?')}}
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Edit/Cancel</button>
                        <button type="button" id="computeScoreNow" class="btn btn-outline-success">Publish Now</button>
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

        //Submit Scores Form after confirmation 
        $("#computeScoreNow" ).click(function() { 
            $("#submitPublishForm").submit();
        }); //end function
        
        //Score Type
        $("#scoreTypeName" ).change(function() { 
            $("#scoreType").val($("#scoreTypeName").val());
        }); //end function

        //Term Name
        $("#termName" ).change(function() { 
            $("#term").val($("#termName").val());
        }); //end function 

        //Dis-select Student 
        $(".selectStudent" ).click(function() { 
            var getID = this.id; 
            if ($('#'+getID).is(":checked")){ 
                $('#'+getID+'scoreText').val('0');
                $('#'+getID+'scoreText').prop('disabled', false);
            }else{ 
                $('#'+getID+'scoreText').val('NO-SCORE');
                $('#'+getID+'scoreText').prop('disabled', true);
            }
        }); //end function

        //Reset Score Type on Confirmation 
        $("#reSetScoreType" ).click(function() { 
            if($("#scoreTypeName").val() == ''){
                alert('Please, you have to select the Score Type you want to compute !');
                return false;
            }
            $("#scoreType").val($("#scoreTypeName").val());
        }); //end function

        //SELECT/DESELECT ALL CHECKBOX
        $('#checkAllStudent').click(function(){
            if($(this).prop("checked")) {
                $(".selectStudent").prop("checked", true);
                $(".scoreInput").val('0');
                $(".scoreInput").prop('disabled', false);
            } else {
                $(".selectStudent").prop("checked", false);
                $(".scoreInput").val('NO-SCORE');
                $(".scoreInput").prop('disabled', true);
            }                
        });

    });//end document
</script>
<!--GET SUBJECT IN CLASS--=== #getClassID === #getClassSubject ===-->
     @include('PartialView.getSubjectListWithClassID')
<!--End get subject-->
@endsection