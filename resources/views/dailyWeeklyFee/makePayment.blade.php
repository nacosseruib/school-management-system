@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper("Student Daily, Weekly, Monthly Make Payment")}} @endsection
@section('studentDailyPaymentPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          
            <div class="row">
                <div class="col-md-12">

                        <div class="card d-print-none">
                            @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                            <div class="card-header">
                                <h4 class="card-title" id="from-actions-multiple">{{ __("STUDENT DAILY, WEEKLY, MONTHLY FEE PAYMENT") }}</h4>
                                <hr />
                                <form class="form" method="post" action="{{route('dailyWeeklyMonthlySearchStudentList')}}">
                                @csrf
                                <div class="row offset-md-1">
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select Class') }} </label>
                                            <select class="form-control" required name="className" id="getClassID">
                                                <option value=""> Select Class </option>
                                                <option value="All"> All classes </option>
                                                @forelse($allClass as $class)
                                                    <option value="{{ $class->classID }}" {{$classID == $class->classID ? 'selected' : ''}} >{{ __($class->class_name) }}</option>
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
                                    
                                     <div class="row">
                                        <div class="offset-md-3 col-md-6 mt-2">
                                            <label> {{ __('Select Fee Type') }} </label>
                                            <select class="form-control" required name="feeType">
                                                <option value=""> Select Fee Type </option>
                                                @forelse($activeDailyFees as $listFee)
                                                    <option value="{{ $listFee->feessetupID }}" {{$feeID == $listFee->feessetupID ? 'selected' :''}} >{!! ($listFee->fees_name) . ' - &#x20a6;' .$listFee->amount !!}</option>
                                                @empty
                                                @endforelse>
                                            </select>
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
                    
                    <form class="form" method="post" action="{{route('processDailyPayment')}}">
                    @csrf
                        <div class="card">
                            <div class="card-header">
                                <div class="d-print-none style="background: #f9f9f9;"">
                                    <div class="row offset-md-1 d-print-none" >
                                        <div class="col-md-4">
                                            <label> Select Payment Date </label>
                                            <input type="date" onkeydown="return false;" name="paymentDate" required class="form-control" />
                                        </div>
                                        <div class="col-md-6">
                                            <label> &nbsp; </label>
                                            <div class="buttons-group text-center">
                                                <button type="submit" id="confirmStudentPromotion" class="btn btn-raised btn-primary mr-1">
                                                    <i class="fa fa-users"></i> {{ __('Make Payment') }}
                                                </button>
                                                <input type="reset" name="reset" value="Reset Amount" class="btn btn-raised btn-default mr-1" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                
                                <div class="text-center text-success"><span class="fa fa-user-o"></span> STUDENT DAILY, WEEKLY, MONTHLY FEE PAYMENT: {{ $className .' - '. $termName .' - '. $sessionCode }}</div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <section id="multi-column">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body collapse show">
                                                        <div class="card-block card-dashboard">
                                                            <table class="table table-hover table-striped">
                                                                <thead style="font-size:13px;">
                                                                    <tr>
                                                                        <td colspan="7" class="text-center text-uppercase"><h6><strong>{{ $feeName }}</strong></h6></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>S/N</th>
                                                                        <th></th>
                                                                        <th>Reg No.</th>
                                                                        <th>Class</th>
                                                                        <th>Surname</th>
                                                                        <th>Others</th>
                                                                        <th class="text-center d-print-none">&#x20a6; Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="font-size:13px;">
                                                                    @forelse($allStudentList as $key => $listStudent)
                                                                        <tr>
                                                                            <td>{{ ($allStudentList->currentpage()-1) * $allStudentList->perpage() + (1+$key) }}</td>
                                                                            <td>
                                                                                <label class="containerCheck">
                                                                                        <input type="text" style="display:none;" value="{{ $listStudent->newStudentID }}" name="studentName[]"> 
                                                                                        <input id="selectStudentName" disabled type="checkbox"  checked="checked">   
                                                                                        <span class="checkmark"></span>
                                                                                </label>
                                                                            </td>
                                                                            <td>{{ $listStudent->student_regID }}</td>
                                                                            <td width="100">{{ $listStudent->class_name }}</td>
                                                                            <td>{{ $listStudent->student_lastname }}</td>
                                                                            <td>{{ $listStudent->student_firstname }}</td>
                                                                            <td class="d-print-none">
                                                                                <input type="number" name="amount[]" class="form-control" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" {{$key ==0 ? 'autofocus' : ''}}  placeholder="Enter Amount" style="min-width: 150px;" />
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="7">
                                                                            <div align="center" class="text-danger"> No Student assigned under this fee! Check Daily Setup to add students. </div>
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