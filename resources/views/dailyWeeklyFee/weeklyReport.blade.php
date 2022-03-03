@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper("Student Daily, Weekly Payment Report")}} @endsection
@section('dailyWeeklyPaymentReportPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
         
            <div class="row">
                <div class="col-md-12">
                        <div class="card d-print-none">
                             @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                            <div class="card-header">
                                <h4 class="card-title" id="from-actions-multiple">{{ __("STUDENT DAILY PAYMENT REPORT") }}</h4>
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
                                 
                                  <div class="row offset-md-1">
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select Fee Type') }} </label>
                                            <select class="form-control" required name="feeType">
                                                <option value=""> Select Fee Type </option>
                                                @forelse($activeDailyFees as $listFee)
                                                    <option value="{{ $listFee->feessetupID }}" {{ $feeType == $listFee->feessetupID ? "selected" : '' }}>{!! ($listFee->fees_name) . ' - &#x20a6;' .$listFee->amount !!}</option>
                                                @empty
                                                @endforelse>
                                            </select>
                                        </div>
                                        <div class="col-md-5 mt-2">
                                            <label> Select Date </label>
                                            <input type="date" name="paymentDate" onkeydown="return false;" required class="form-control" value="{{ ($paymentDate <> null ? $paymentDate : old('paymentDate')) }}" />
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
                               
                                <div class="text-center text-success"><span class="fa fa-user-o"></span> STUDENT DAILY PAYMENT REPORT: {{ $className .' - '. $termName .' - '. $sessionCode }}</div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <section >
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body collapse show">
                                                        <div class="card-block card-dashboard">
                                                            <table class="table table-hover table-striped table-responsive">
                                                                <thead class="bg-light text-center">
                                                                    <tr>
                                                                        <th>S/N</th>
                                                                        <th>Reg No.</th>
                                                                        <th>Full Name</th>
                                                                        <th>Class Name</th>
                                                                        <th>Day</th>
                                                                        <th>Amount Paid</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="6" class="text-center text-uppercase"><h6><strong>{!! $feeName .': &#x20a6;'. $feeAmountPerPayment !!}</strong></h6></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-left" style="font-size:13px;">
                                                                    @php $totalPaid = 0;  @endphp
                                                                    @forelse($allStudentList as $key => $listStudent)
                                                                        <tr>
                                                                            <td>{{ ($allStudentList->currentpage()-1) * $allStudentList->perpage() + (1+$key) }}</td>
                                                                            <td>{{ $listStudent->student_regID }}</td>
                                                                            <td>{{ $listStudent->student_lastname .' '. $listStudent->student_firstname }}</td>
                                                                            <td>{{ $listStudent->class_name }}</td>
                                                                            <td>{{ (isset($paymentDate) ? date('dS F, Y', strtotime($paymentDate)) : '') }}</td>
                                                                            <td>&#x20a6;{{ number_format($studentAmount[$key][$listStudent->newStudentID], 2) }}</td>
                                                                        </tr>
                                                                        @php $totalPaid += $studentAmount[$key][$listStudent->newStudentID];  @endphp
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="6">
                                                                            <div align="center" class="text-danger"> No Record Found ! </div>
                                                                       </td>
                                                                    </tr>
                                                                    @endforelse
                                                                     <tr>
                                                                        <td colspan="6">
                                                                            <div class="text-center text-uppercase text-success"><h6><strong>Payment Summary For Today ({{(isset($paymentDate) ? date('dS F, Y', strtotime($paymentDate)) : '')}})</strong></h6></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"> 
                                                                            <div class="text-center text-uppercase text-info"><h6><strong>Total Amount Due Today:  <b>&#x20a6;{{ number_format(($feeAmountPerPayment * count($allStudentList)), 2) }}</b> </strong></div>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="text-center text-uppercase text-success"><h6><strong>Total Amount Paid Today: <b>&#x20a6;{{ number_format($totalPaid, 2) }}</b> </strong></div>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="text-center text-uppercase text-danger"><h6><strong>Total Outstanding Bal. Today: </span>  <b>&#x20a6;{{ number_format( (($feeAmountPerPayment * count($allStudentList)) - $totalPaid), 2) }}</b> </strong></div>
                                                                        </td>
                                                                    </tr>
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