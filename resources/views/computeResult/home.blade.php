@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Submit Scores/Marks ::'. Auth::user()->name)) }} @endsection
@section('computeResultPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

          <div class="hidden-print">
          <form class="form" method="post" action="{{route('findStudentClass')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('SUBMIT SCORES/MARKS') }}</h4>
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
                                    <label> {{ __('Current Term') }} </label>
                                    <select class="form-control" required readonly name="termName" id="termName">
                                        <!--<option value="{{ ($getSession) ? $getSession->termID : '' }}" selected> {{ ($getSession) ? $getSession->term_name : '' }}</option>-->
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
                                <div class="col-md-4 mt-2">
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
                                <div class="col-md-4 mt-2">
                                    <label> {{ __('Select Score Type') }} </label>
                                    <select required class="form-control" name="scoreTypeName" id="scoreTypeName"> 
                                        <option value="{{Session::get('scoretypeID')}}">@if(Session::get('setScoreTypeName')) {{Session::get('setScoreTypeName')}} @else Select Score Type @endif</option>
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
                                            <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                <i class="fa fa-check-square-o"></i> {{ __('Get Student and Continue') }}
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
            </div>

            <section id="extended">
            <form class="form" id="submitScoreForm" method="post" action="{{route('submitScores')}}">
            @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title"><h5>{{ __('Available Student found - ') }} @if(Session::get('subjectNameSet')) {!! '  <b class="text-danger"><small> ' . Session::get('termNameSet') . ' | Enter Scores for '. Session::get('subjectNameSet') . ' - '. Session::get('setScoreTypeName')  .'</small></b>' !!} @else <span class="text-warning">Select Class, Subject & Score Type</span> @endif</h5></div>
                            </div>
                            <div class="card-body">
                                <div class="card-block">
                                    <table class="table table-striped table-responsive table-condensed text-center">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S/N') }}</th>
                                                <th>{{ __('Surname') }}</th>
                                                <th>{{ __('Other Names') }}</th>
                                                <th>{{ __('Gender') }}</th>
                                                <th class="text-center">{{ __('Score') }}</th>
                                                <th>
                                                    <div class="custom-control custom-checkbox m-0">
                                                        <input checked type="checkbox" id="checkAllStudent"  name="checkAllStudent" class="custom-control-input">
                                                        <label class="custom-control-label" for="checkAllStudent"></label>
                                                    </div>
                                                </th>
                                                <th class="text-center">Test&nbsp;1</th>
                                                <th class="text-center">Test&nbsp;2</th>
                                                <th class="text-center">Exam</th>
                                                <th>
                                                    @if($getSession)
                                                    @if($getSession->allow_result_computation == 1)
                                                        <button type="button" id="reSetScoreType" data-toggle="modal" data-backdrop="false" data-target="#confirmComputation" class="btn btn-sm btn-success"> {{ __('Compute For All')}} </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-danger"> <i class="ft-x font-medium-3 mr-2"></i> Not Allowed to Compute</button>
                                                    @endif
                                                    @else
                                                    <button type="button" class="btn btn-sm btn-danger"> <i class="ft-x font-medium-3 mr-2"></i> Not Allowed to Compute</button>
                                                    @endif
                                                    <input type="hidden" name="className" value="{{ Session::get('classIDSet') ? Session::get('classIDSet') : '' }}" />
                                                    <input type="hidden" name="subjectName" value="{{ Session::get('subjectIDSet') ? Session::get('subjectIDSet') : '' }}" />
                                                    <input type="hidden" name="scoreType" id="scoreType" value="{{ Session::get('scoretypeID') ? Session::get('scoretypeID') : '' }}" />
                                                    <input type="hidden" name="term" id="term" value="{{ Session::get('termIDSet') ? Session::get('termIDSet') : '' }}" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($foundStudent as $keyStd=>$listStudent)
                                            <tr>
                                                <td>{{ (1 + $keyStd) }}</td>
                                                <td>{{ $listStudent->student_lastname }}</td>
                                                <td>{{ $listStudent->student_firstname }}</td>
                                                <td>{{ $listStudent->student_gender }}</td>
                                                <td>
                                                    <div class="pull-right position-relative has-icon-right form-group mb-2">
                                                        <input style="width:140px;" type="text" value="0" name="score[]" class="form-control scoreInput" id="item{{$listStudent->studentsID}}scoreText" @if($keyStd == 0) autofocus @endif placeholder="Enter Score">
                                                        <div class="form-control-position" style="margin-right:15px; margin-top:-4px;">
                                                            <span class="text-secondary">/<b>{{ (Session::get('maxScore') ? Session::get('maxScore') : 100) }}</b></span>
                                                        </div>
                                                    </div>
                                                    <span id="scoreMessage{{$listStudent->studentsID}}" class="text-danger" style="font-size:12px;"></span>
                                                </td>
                                                <td width="20">
                                                    <div class="custom-control custom-checkbox m-0">
                                                        <input checked type="checkbox" id="item{{ $listStudent->studentsID }}" name="studentIdSelected[]" value="{{ $listStudent->studentsID }}" class="selectStudent custom-control-input" >
                                                        <label class="custom-control-label" for="item{{ $listStudent->studentsID }}"></label>
                                                    </div>
                                                </td>
                                                <td width="20">
                                                     {!! (($markTest1[$keyStd.$listStudent->studentsID]) ? $markTest1[$keyStd.$listStudent->studentsID] : '<i class="ft-user font-medium-3 mr-2"></i>') !!} 
                                                </td>
                                                <td width="20">
                                                    {!! (($markTest2[$keyStd.$listStudent->studentsID]) ? $markTest2[$keyStd.$listStudent->studentsID] : '<i class="ft-user font-medium-3 mr-2"></i>') !!} 
                                                </td>
                                                <td width="20">
                                                    {!! (($markExam[$keyStd.$listStudent->studentsID]) ? $markExam[$keyStd.$listStudent->studentsID] : '<i class="ft-user font-medium-3 mr-2"></i>') !!} 
                                                </td>
                                                <td width="20">
                                                    <a class="info p-0" data-toggle="modal" data-backdrop="false" data-target="#viewStudent{{$listStudent->studentsID}}" class="btn btn-sm btn-success">
                                                        <i class="ft-user font-medium-3 mr-2"></i>
                                                    </a>
                                                </td>

                                            </tr>
                                            @php $keyStd ++; @endphp

                                            <!--Student Details  Modal -->
                                            <div class="modal fade text-left" id="viewStudent{{$listStudent->studentsID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success white">
                                                        <h4 class="modal-title" id="myModalLabel12"><i class="ft-user font-medium-3 mr-2"></i> Student Information  </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">

                                                                <div  align="center" class="col-md-12"> 
                                                                    <img width="120" src="{{ (($listStudent->photo) ? asset($studentPath . $listStudent->photo) : asset($studentPath .'noPicture.png')) }}" class="img-thumbnail img-responsive" alt=" " />
                                                                    <br />
                                                                    <br />
                                                                </div>
                                                                <div class="col-md-12" style="background: #f9f9f9;"> 
                                                                    <label>Student Reg. No.: </label>
                                                                    <b>{{ $listStudent->student_regID }}</b>
                                                                </div>
                                                                <div class="col-md-12" style="background: #f9f9f9;"> 
                                                                    <label>Roll No.: </label>
                                                                    <b>{{ $listStudent->student_roll }}</b>
                                                                </div>
                                                                <div class="col-md-12"> 
                                                                    <label>Class/Level: </label>
                                                                    <b>{{ $listStudent->class_name }}</b>
                                                                </div>
                                                                <div class="col-md-12" style="background: #f9f9f9;"> 
                                                                    <label>Surname: </label>
                                                                    <b>{{ $listStudent->student_lastname }}</b>
                                                                </div>
                                                                <div class="col-md-12"> 
                                                                    <label>Other Name: </label>
                                                                    <b>{{ $listStudent->student_firstname }}</b>
                                                                </div>
                                                                <div class="col-md-12"> 
                                                                    <label>Gender: </label>
                                                                    <b>{{ $listStudent->student_gender }}</b>
                                                                </div>
                                                                <div class="col-md-12" style="background: #f9f9f9;"> 
                                                                    <label>Parent Telephone: </label>
                                                                    <b>{{ $listStudent->parent_telephone  }}</b>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-success" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end Modal-->
                                        @empty
                                            <tr>
                                                <td colspan="10"> <span class="text-danger text-center"><b>No student found !</b></span> </td>
                                            </tr>
                                        @endforelse
                                            <tr>
                                                   
                                                <td align="right" colspan="10">
                                                    @if($getSession)
                                                    @if( $getSession->allow_result_computation == 1)
                                                        <button type="button" id="reSetScoreType" data-toggle="modal" data-backdrop="false" data-target="#confirmComputation" class="btn btn-sm btn-success"> {{ __('Compute For All')}} </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-danger"> <i class="ft-x font-medium-3 mr-2"></i> Not Allowed to Compute</button>
                                                    @endif
                                                    @else
                                                    <button type="button" class="btn btn-sm btn-danger"> <i class="ft-x font-medium-3 mr-2"></i> Not Allowed to Compute</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--//row-->
            </form>
        </section>

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

    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //Submit Scores Form after confirmation 
        $("#computeScoreNow" ).click(function() { 
            $("#submitScoreForm").submit();
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