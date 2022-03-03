@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('View Mark Sheet :: '. Auth::user()->name)) }} @endsection
@section('viewMarkSheetPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

          <form class="form" method="post" action="{{route('searchMarkSheet')}}">
          @csrf
            <div class="row d-print-none">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('MARK SHEET') }}</h4>
                            <hr />
                            <div class="alert alert-secondary" role="alert">
                                <strong>{{ __('Select Class and Subject') }}</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label> {{ __('Current Session') }} </label>
                                    <div class="form-control" name="session" readonly>
                                        {!! ($getSession) ? $getSession->session_name : '<span class="text-danger">Please, Set School Session</span>' !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label> Teacher's Name </label>
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
                                        <option value="{{Session::get('classIDSet')}}">@if(Session::get('classNameSet')) {{Session::get('classNameSet')}} @else Select Class @endif</option>
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
                                <div class="col-md-4  mt-2">
                                    <label> {{ __('Select Subject') }} </label>
                                    <select class="form-control" required name="subjectName" id="getClassSubject">
                                        <option value=""> Select Subject</option>
                                        <!--@forelse($allSubject as $subject)
                                            <option value="{{ $subject->subjectID }}">{{ __($subject->subject_name) }}</option>
                                        @empty
                                        @endforelse-->
                                    </select>
                                    @if ($errors->has('subjectName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('subjectName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                            
                        </div>
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group float-right">
                                            <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                <i class="fa fa-check-square-o"></i> {{ __('Get Mark Sheet') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            </form>	

            @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
            <form id="formSelectedScoreDeletion" class="form" method="post" action="{{route('deleteSelectedMark')}}">
            @csrf
            <section id="extended">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">MARK SHEET - {{ (Session::get('classNameSet')) ? 'FOR ' . Session::get('classNameSet') : 'Select Class' }} <span class="text-success">@if(Session::get('subjectNameSet')) {{ ' - ' .Session::get('subjectNameSet') }} @else  @endif </span></h6>
                            </div>
                            <div class="card-body">
                                <div class="card-block">
                                    <table class="table table-striped table-hover table-responsive table-condensed">
                                        <thead>
                                            <tr style="font-size:13px; background:#f9f9f9;">
                                                <th>SN</th>
                                                <th>{{ __('Reg. No.') }}</th>
                                                <th>{!! __('Full&nbsp;Names') !!}</th>
                                                <th>{!! __('Gender') !!}</th>
                                                <th class="text-center">1st&nbsp;Test<br/>({{ (Session::get('maxSubjectScore') ? Session::get('maxSubjectScore')->max_ca1 : '')}})</th>
                                                <th class="text-center">2nd&nbsp;Test<br/>({{ (Session::get('maxSubjectScore') ? Session::get('maxSubjectScore')->max_ca2 : '')}})</th>
                                                <th class="text-center">Exam<br/>({{ (Session::get('maxSubjectScore') ? Session::get('maxSubjectScore')->max_exam : '')}})</th>
                                                <th class="text-center">Total<br />(100)</th>
                                                <!--<th class="text-center">Score<br />(%)</th>-->
                                                <th class="text-center">Grade</th>
                                                <th class="text-center">Remark</th>
                                                <th class="text-center">Submitted By</th>
                                                <th class="text-center">Date</th>
                                                <th colspan="2" class="text-center d-print-none">
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-backdrop="false" data-target="#confirmDeleteSelectedStudent"><i class="fa fa-trash"></i></button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($foundStudent)
                                        @forelse($foundStudent as $keyStd=>$listStudent)
                                            <tr style="font-size:12px; color: {{ ($markPercentage[$keyStd.$listStudent->studentsID] >39) ? 'green' : 'red' }};">
                                                <td style="width: 4px;">{{ (1 + $keyStd) }} </td>
                                                <td class="text-left">{{ $listStudent->student_regID }}</td>
                                                <td class="text-left">{{ $listStudent->student_firstname .' '. $listStudent->student_lastname }}</td>
                                                <td>{{ $listStudent->student_gender }}</td>
                                                <td style="background:#f9f9f9;">
                                                    <!--Test 1-->
                                                     {!! (($markTest1[$keyStd.$listStudent->studentsID]) ? $markTest1[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!} 
                                                </td>
                                                <td>
                                                    <!--Test 2-->
                                                    {!! (($markTest2[$keyStd.$listStudent->studentsID]) ? $markTest2[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!} 
                                                </td>
                                                <td style="background:#f9f9f9;">
                                                    <!--Exam-->
                                                    {!! (($markExam[$keyStd.$listStudent->studentsID]) ? $markExam[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!} 
                                                </td>
                                                <td>
                                                    <!--Total-->
                                                    <b>
                                                    {!! (($markTotal[$keyStd.$listStudent->studentsID]) ? $markTotal[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                    </b>
                                                </td>
                                                <!--<td style="background:#f9f9f9;">
                                                    % Score
                                                    {!! (($markPercentage[$keyStd.$listStudent->studentsID]) ? $markPercentage[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                </td>-->
                                                <td>
                                                    <!--Grade-->
                                                    {!! (($markGrade[$keyStd.$listStudent->studentsID]) ? $markGrade[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}   
                                                </td>
                                                <td style="background:#f9f9f9;">
                                                    <!--Remark-->
                                                    {!! (($markRemark[$keyStd.$listStudent->studentsID]) ? $markRemark[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                </td>
                                                <td>
                                                    <!--computed by-->
                                                    {!! (($computedBy[$keyStd.$listStudent->studentsID]) ? $computedBy[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                </td>
                                                <td style="font-size:10px;">
                                                    <!--cdate last updated-->
                                                    {!! (($dateComputed[$keyStd.$listStudent->studentsID]) ? $dateComputed[$keyStd.$listStudent->studentsID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                </td>
                                                <td>
                                                    @if($markID[$keyStd.$listStudent->studentsID])
                                                        <input type="checkbox" class="checkBox selectCheckBoxOnTap" tabindex="1" id="{{$markID[$keyStd.$listStudent->studentsID]}}" name="selectedStudentCheckbox[{{$markID[$keyStd.$listStudent->studentsID]}}]" value="{{$markID[$keyStd.$listStudent->studentsID]}}">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($markID[$keyStd.$listStudent->studentsID])
                                                       <button type="button" class="btn btn-danger btn-sm disableSingleDeletion" data-toggle="modal" data-backdrop="false" data-target="#markID{{$markID[$keyStd.$listStudent->studentsID]}}"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                            
                                            <!--Confirm Delete  Modal -->
                                            <div class="modal fade text-left" id="markID{{$markID[$keyStd.$listStudent->studentsID]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger white">
                                                        <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete !')}}  </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <h6 class="text-success"><i class="fa fa-arrow-right"></i> {{ __('Delete '. Session::get('subjectNameSet') .' for ' . $listStudent->student_regID .' - '. $listStudent->student_firstname .' '. $listStudent->student_lastname  )}} </h6>
                                                        <p class="text-center text-danger">
                                                            {{ __('Are you sure you want to delete this score/mark for this student ?')}}
                                                        </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-success" data-dismiss="modal">Cancel</button>
                                                            <a href="{{ route('deleteMark', ['markID'=>$markID[$keyStd.$listStudent->studentsID]])}}" class="btn btn-outline-danger">Delete Now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end Modal-->
                                            
                                            @php $keyStd ++; @endphp
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center"> <span class="text-danger"><b>No student found !</b></span> </td>
                                            </tr>
                                        @endforelse
                                        @else
                                            <tr>
                                                <td colspan="13" class="text-center"> <span class="text-danger text-center"><b>No student found !</b></span> </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--//row-->
            </section>
            </form>	

            
        <!--Confirm Computation  Modal -->
        <div class="modal fade text-left" id="confirmComputation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Confirm Your Operation')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5 class="text-warning"><i class="fa fa-arrow-right"></i> {{ __('Computation will be done for all selected Students.')}} </h5>
                      <p class="text-center text-success">
                        {{ __('Are you sure you want to continue with your computations ?')}}
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Edit/Cancel</button>
                        <button type="button" id="computeScoreNow" class="btn btn-outline-success">Compute Now</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end Modal-->
        
        
        <!--Confirm  Multiple Deletion Modal -->
        <div class="modal fade text-left" id="confirmDeleteSelectedStudent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger white">
                        <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Seleted Record!')}}  </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-success"><i class="fa fa-arrow-right"></i> {{ __('Delete all selected record')}} </h6>
                            <p class="text-center text-danger">
                                {{ __("Are you sure you want to delete the selected student's score(s)  ?")}}
                            </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-success" data-dismiss="modal">Cancel</button>
                        <button type="button" id="submitSelectedScoreDeletion" class="btn btn-outline-danger">Delete All Now</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end Modal for Multiple deletion-->

    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        
        //Submit Scores Form after confirmation 
        $(".selectCheckBoxOnTap").click(function() { 
            var checkBoxID = $(this).attr('id');
            var checkClick = $(".selectCheckBoxOnTap").is(":checked");
            if(checkClick)
            { 
              // checked
              $(".disableSingleDeletion").attr("disabled", true);
            }else{ 
             // unchecked
                $(".disableSingleDeletion").attr("disabled", false);
            }
        }); //end function
        
        //Submit Scores Form after confirmation 
        $("#computeScoreNow" ).click(function() { 
            $("#submitScoreForm").submit();
        }); //end function
        
        //Submit Scores Multiple Deletion after confirmation 
        $("#submitSelectedScoreDeletion" ).click(function() { 
            $("#formSelectedScoreDeletion").submit();
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
                Alert('Please, you have to select the Score Type you want to compute !');
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