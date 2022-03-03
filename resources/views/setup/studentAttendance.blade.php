@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ ('Student Attendance') }} @endsection
@section('studentAttandancePageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

                <div class="card d-print-none">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-multiple">{{ __("SEARCH STUDENTS") }}</h4>
                        <hr />
                        @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                        <form class="form" method="post" action="{{route('searchStudentAttendance')}}">
                        @csrf
                      
                        <div class="row offset-md-1 d-print-none">
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Term') }} </label>
                                    <select class="form-control" name="term">
                                        <option value=""> Select Term </option>
                                        @forelse($term as $listTerm)
                                            <option value="{{ $listTerm->termID }}" {{ ($listTerm->termID == old('term') ? 'selected' : '') }}>{{ __($listTerm->term_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('term'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('term') }}</strong>
                                        </span>
                                    @endif  
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Session') }} </label>
                                    <select class="form-control" name="session">
                                        <option value=""> Select Session </option>
                                        @forelse($session as $listSession)
                                            <option value="{{ $listSession->session_code }}" {{ ($listSession->session_code == old('session') ? 'selected' : '') }} >{{ __($listSession->session_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('session'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('session') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                        
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group text-center">
                                            <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                <i class="fa fa-check-square-o"></i> {{ __('Search') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </form>	
                </div>
            </div>
           
           <!--//-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ strtoupper('STUDENT ATTENDANCE SETUP') }}</h4>
                            <hr />

                            <form class="form" method="post" action="{{route('postStudentAttandance')}}">
                            @csrf
                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Class') }} </label>
                                    <select class="form-control" required name="className" id="getClassID">
                                        <option value=""> Select Class </option>
                                        <option value="All"> All classes </option>
                                        @forelse($allClass as $class)
                                            <option value="{{ $class->classID }}" {{ ($class->classID == old('className') ? 'selected' : '') }}>{{ __($class->class_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('className'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('className') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Student Name') }} </label>
                                    <select class="form-control" required name="studentName" id="studentName">
                                        <option value=""> Select Student </option>
                                    </select>
                                    @if ($errors->has('studentName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('studentName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row offset-md-1-->
                            <div class="row offset-md-1 d-print-none">
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Term') }} </label>
                                    <select class="form-control" required name="term">
                                        <option value=""> Select Term </option>
                                        @forelse($term as $listTerm)
                                            <option value="{{ $listTerm->termID }}" {{ ($listTerm->termID == old('term') ? 'selected' : '') }}>{{ __($listTerm->term_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('term'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('term') }}</strong>
                                        </span>
                                    @endif  
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Session') }} </label>
                                    <select class="form-control" required name="session">
                                        <option value=""> Select Session </option>
                                        @forelse($session as $listSession)
                                            <option value="{{ $listSession->session_code }}" {{ ($listSession->session_code == old('session') ? 'selected' : '') }} >{{ __($listSession->session_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('session'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('session') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                            <div class="row offset-md-1 d-print-none">
                                <div class="col-md-4 mt-2">
                                    <label> {{ __('Total Days School Opens') }} </label>
                                    <div align="center" class="custom-control form-control">
                                        {{ $daysSchoolOpens }}
                                    </div>
                                    @if ($errors->has('term'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('term') }}</strong>
                                        </span>
                                    @endif  
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label> {{ __('Total Days Present') }} </label>
                                    <input type="text" class="form-control" required value="{{old('totalDaysPresent')}}" name="totalDaysPresent" placeholder="Present"  />
                                    @if ($errors->has('totalDaysPresent'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('totalDaysPresent') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-3 mt-2">
                                    <label> {{ __('Total Days Absent') }} </label>
                                    <input type="text" class="form-control" required value="{{old('totalDaysAbsent')}}" name="totalDaysAbsent" placeholder="Absent" />
                                    @if ($errors->has('totalDaysAbsent'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('totalDaysAbsent') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-10 mt-2">
                                    <label> {{ __('Other Comment For Parent') }} </label>
                                    <textarea class="form-control" value="{{old('otherComment')}}" name="otherComment" placeholder="Add Other Comment for Parent"></textarea>
                                    @if ($errors->has('otherComment'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('otherComment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                            <div class="row">
                                <div align="center" class="col-md-12 mt-2"> 
                                    <label> &nbsp; </label>
                                    <div> 
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update</button>
                                    </div>
                                </div>
                            </div><!--//row-->
                        </form>
                        <hr />

                            <div class="row">
                                <div align="center" class="col-md-12">
                                    <table class="table table-hover table-stripped table-responsive table-condensed" style="font-size: 13px;"> 
                                    <thead>
                                        <tr style="background:#d9d9d9">
                                            <th>{{ __('S/N') }}</th>
                                            <th class="text-left">{{ __("Student's Name") }}</th>
                                            <th>{{ __('Class') }}</th>
                                            <th>{{ __('Gender') }}</th>
                                            <th>{{ __('School Opens') }}</th>
                                            <th>{{ __('Present') }}</th>
                                            <th>{{ __('Absent') }}</th>
                                            <th>{{ __('Term/Session') }}</th>
                                            <th>{{ __('Comment') }}</th>
                                            <th>{{ __('Last Updated') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($studentAttandance))
                                        @forelse($studentAttandance as $key=>$listStudent)
                                        <tr class="input-sm">
                                            <td>{{($studentAttandance->currentpage()-1) * $studentAttandance->perpage() + (1 + $key)}}</td>

                                            <td align="left"> {{ $listStudent->student_lastname .' '. $listStudent->student_firstname}} </td>

                                            <td> {{ $listStudent->className}} </td>

                                            <td> {{ $listStudent->student_gender}} </td>
                                            
                                            <td style="background:#f9f9f9;"> 
                                                {{ $listStudent->total_school_open }}
                                            </td>
                                            <td style="background:#f9f9f9;"> 
                                                {{ $listStudent->total_present }}
                                            </td>
                                            <td style="background:#f9f9f9;"> 
                                                {{ $listStudent->total_absent }}
                                            </td>
                                            <td> 
                                                {{ $listStudent->term_name .' '. $listStudent->session_code }}
                                            </td>
                                            <td class="text-left"> 
                                                {{ $listStudent->comment }}
                                            </td>
                                            <td> 
                                                {{ $listStudent->attendanceDate }}
                                            </td>
                                            
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-danger"> No Attendance Record found for any student yet! </td>
                                            </tr>
                                        @endforelse
                                        @endif
                                    </thead>
                                    </table> 
                                </div>
                                <div align="right" class="col-md-12"><hr />
                                    Showing {{($studentAttandance->currentpage()-1)*$studentAttandance->perpage()+1}}
                                            to {{$studentAttandance->currentpage()*$studentAttandance->perpage()}}
                                            of  {{$studentAttandance->total()}} entries
                                </div>
                                <div class="d-print-none">{{ $studentAttandance->links() }}</div>
                            </div><!--//row-->
                    </div>
                </div>
            </div>
        </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //Student
        $(".student" ).click(function() { 
            var getID = this.id; 
            var studentID = $('#getStudentID' + getID).val();
            if($(this).prop("checked")) { 
                $('#student' + getID).val(studentID);
            }else{ 
                $('#student' + getID).val(0);
            }
        }); //end function

    });//end document
</script>
<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
    @include('PartialView.getSudentListWithClassID')
<!--End get subject-->
@endsection