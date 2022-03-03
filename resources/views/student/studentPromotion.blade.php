@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper("Student Promotion's Portal")}} @endsection
@section('studentPromotionPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          
            <div class="row">
                <div class="col-md-12">

                        <div class="card d-print-none">
                            <div class="card-header">
                                <h4 class="card-title" id="from-actions-multiple">{{ __("STUDENT PROMOTION") }}</h4>
                                <hr />
                                <form class="form" method="post" action="{{route('searchStudentList')}}">
                                @csrf
                                <div class="row offset-md-1">
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select Class') }} </label>
                                            <select class="form-control" required name="className" id="getClassID">
                                                <option value=""> Select Class </option>
                                                <option value="All"> All classes </option>
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
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select Student Name') }} </label>
                                            <select class="form-control" name="studentName" id="studentName">
                                                <option value=""> Select Student </option>
                                            </select>
                                            @if ($errors->has('studentName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('studentName') }}</strong>
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
                    
                    <form class="form" method="post" action="{{route('processStudentPromotion')}}">
                    @csrf
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label> &nbsp; </label>
                                        <h4 class="card-title" id="from-actions-multiple">{{ __("LIST OF STUDENTS") }}</h4>
                                    </div>
                                    <div class="col-md-8 row" style="background: #f9f9f9;">
                                        <div class="col-md-8">
                                            <label> {{ __('Select New Class') }} </label>
                                            <select class="form-control" required name="studentNewClass">
                                                <option value=""> Select Class </option>
                                                @forelse($allClass as $class)
                                                    <option value="{{ $class->classID }}">{{ __($class->class_name) }}</option>
                                                @empty
                                                @endforelse
                                                <option value="123456"> Graduate </option>
                                                <option value="1234567"> Withdraw </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label> &nbsp; </label>
                                            <div class="buttons-group text-center">
                                                <button type="submit" id="confirmStudentPromotion" class="btn btn-raised btn-primary mr-1">
                                                    <i class="fa fa-users"></i> {{ __('Continue') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <section id="multi-column">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body collapse show">
                                                        <div class="card-block card-dashboard">
                                                            <table class="table table-hover table-striped table-responsive table-condensed">
                                                                <thead style="font-size:13px;">
                                                                    <tr>
                                                                        <th>S/N</th>
                                                                        <th>
                                                                            <label class="containerCheck">
                                                                              <input type="checkbox" checked="checked" id="selectAllStudentCheck" class="mb-2">
                                                                              <span class="checkmark"></span>
                                                                            </label><br />
                                                                        </th>
                                                                        <th>Reg No.</th>
                                                                        <th>Roll</th>
                                                                        <th>Class</th>
                                                                        <th>Surname</th>
                                                                        <th>Others</th>
                                                                        <th>Gender</th>
                                                                        <th class="d-print-none">Status</th>
                                                                        <th class="d-print-none" colspan="2">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="font-size:13px;">
                                                                    @forelse($allStudentList as $key => $listStudent)
                                                                    <tr>
                                                                        <td>{{ ($allStudentList->currentpage()-1) * $allStudentList->perpage() + (1+$key ++) }}</td>
                                                                        <td>
                                                                            <label class="containerCheck">
                                                                              <input id="selectStudentName" value="{{ $listStudent->newStudentID }}" {!! $listStudent->studentActive == 1 ? ' name="studentName[]" type="checkbox" checked="checked"' : '' !!} >
                                                                              <span class="checkmark"></span>
                                                                            </label>
                                                                        </td>
                                                                        <td>{{ $listStudent->student_regID }}</td>
                                                                        <td>{{ $listStudent->student_roll }}</td>
                                                                        <td width="100">{{ $listStudent->class_name }}</td>
                                                                        <td>{{ $listStudent->student_lastname }}</td>
                                                                        <td>{{ $listStudent->student_firstname }}</td>
                                                                        <td>{{ $listStudent->student_gender }}</td>
                                                                        <td class="d-print-none"> 
                                                                            @if($listStudent->studentActive == 1)
                                                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-backdrop="false" data-target="#editStatusModal{{$listStudent->newStudentID}}">Active</button>
                                                                            @else
                                                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-backdrop="false" data-target="#editStatusModal{{$listStudent->newStudentID}}" style="font-size:10px;">Not <br> Active</button>
                                                                            @endif
                                                                        </td>
                                                                        <td class="d-print-none"><a href="{{ route('studentDetails', ['studentID' => $listStudent->newStudentID]) }}" class="btn btn-sm btn-primary" title="View Student Details"> <i class="fa fa-eye"></i> </a></td>
                                                                        <td class="d-print-none"><a href="{{ route('editStudent', ['studentID' => $listStudent->newStudentID]) }}" class="btn btn-sm btn-secondary"> <i class="fa fa-edit"></i> </a></td>
                                                                    </tr>
    
                                                                    <!--Change Active/Deactive Student Modal-->
                                                                    <div class="modal fade text-left" id="editStatusModal{{$listStudent->newStudentID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-success white">
                                                                                <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-edit"></i> {{ __('Change Status')}}  </h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div>
                                                                                        <div class="text-center"><b> {{ $listStudent->student_lastname .' '. $listStudent->student_firstname }} </b></div>
                                                                                        <label>Select Student Status</label>
                                                                                        <select id="statusID{{ $listStudent->newStudentID }}" name="studentActive" class="form-control">
                                                                                            <option value="">Select</option>
                                                                                            <option value="1">Activate</option>
                                                                                            <option value="0">Deactivate</option>
                                                                                        </select>
                                                                                    </div>
                                                                                <div class="modal-footer"> 
                                                                                    <span id="pleaseWait{{$listStudent->newStudentID}}" class="text-left"></span>
                                                                                    <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Close</button>
                                                                                    <button type="button" id="{{ $listStudent->newStudentID }}" class="btn btn-outline-success changeStatus"> Change and Save </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--end Modal-->
    
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="11">
                                                                            <div align="center" class="text-danger"> No Student Found ! </div>
                                                                       </td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                            <div class="row">
                                                                <div align="right" class="col-xs-12 col-sm-12">
                                                                    Showing {{($allStudentList->currentpage()-1)*$allStudentList->perpage()+1}}
                                                                    to {{$allStudentList->currentpage()*$allStudentList->perpage()}}
                                                                    of  {{$allStudentList->total()}} entries
                                                                    <br />
                                                                    <div class="hidden-print">{{ $allStudentList->links() }}</div> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
    
    
                                    </div>
                                </div><!--//row-->
    
                            </div>
                        </div><!--//card-->
                    </div><!--col-12-->
                </form>
            </div><!--//row-->
            
                
    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('styles')
    <style>
        /* Customize the label (the container) */
        .containerCheck {
          display: block;
          position: relative;
          padding-left: 35px;
          margin-bottom: 12px;
          cursor: pointer;
          font-size: 22px;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
        }
        
        /* Hide the browser's default checkbox */
        .containerCheck input {
          position: absolute;
          opacity: 0;
          cursor: pointer;
          height: 0;
          width: 0;
        }
        /* Create a custom checkbox */
        .checkmark {
          position: absolute;
          top: 0;
          left: 0;
          height: 25px;
          width: 25px;
          background-color: #eee;
        }
        
        /* On mouse-over, add a grey background color */
        .containerCheck:hover input ~ .checkmark {
          background-color: #ccc;
        }
        
        /* When the checkbox is checked, add a blue background */
        .containerCheck input:checked ~ .checkmark {
          background-color: #2196F3;
        }
        
        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
          content: "";
          position: absolute;
          display: none;
        }
        
        /* Show the checkmark when checked */
        .containerCheck input:checked ~ .checkmark:after {
          display: block;
        }
        
        /* Style the checkmark/indicator */
        .containerCheck .checkmark:after {
          left: 9px;
          top: 5px;
          width: 5px;
          height: 10px;
          border: solid white;
          border-width: 0 3px 3px 0;
          -webkit-transform: rotate(45deg);
          -ms-transform: rotate(45deg);
          transform: rotate(45deg);
        }
    </style>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
    
        $(".changeStatus").click(function() { 
            var studentID = this.id;
            $('#pleaseWait' + studentID).html('').hide();
            var statusID = $("#statusID" + studentID).val();
            if(studentID < 0 || studentID == ''){
                alert('Sorry, we cannot process this operation at this moment. Try again !');
                return false;
            }
            if(statusID < 0 || statusID == ''){
                alert('Sorry, we cannot process this operation at this moment. Try to select from the list again !');
                $("#statusID").focus();
                return false;
            }
            $('#pleaseWait' + studentID).html('Please wait, processing...').css('color','red').show();
            $.ajax({
                url: "{{url('/')}}" + '/activate-deactivate-student/' + studentID +'/' + statusID,
                type: "get",
                success: function(data){
                    $('#pleaseWait' + studentID).html(data).css('color','green').fadeIn(1000);
                    $('#editStatusModal' + studentID).hide();
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('An error occurred! Looks like your session has expired or you are not connected to the internet.');
                    $('#pleaseWait' + studentID).html('').hide();
                }
            });
        });

    });//end document
    
    /*Check all checkbox*/
    $(document).ready(function() {
        $("#selectAllStudentCheck").click(function() {
            if(this.checked){
                $("input[type=checkbox]").prop("checked", true);
                //$("#selectStudentName").prop("checked", true);
            }else { 
                $("input[type=checkbox]").prop("checked", false);
            }
        });
    });
    
</script>

<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
@include('PartialView.getSudentListWithClassID')
<!--End get subject-->

@endsection