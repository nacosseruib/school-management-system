@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper('All Fees Report') }} @endsection
@section('PaymentFeeReportHeaderTitle') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

                <div class="card d-print-none">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-multiple">{{ __("SEARCH STUDENTS") }}</h4>
                        <hr />

                            @include('PartialView.searchCurrentFormerStudent')
                            
                    </div>
                </div>
           
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="d-print-none">
                                <h4 class="card-title" id="from-actions-multiple">{{ __('ALL PAYMENT REPORT') }}</h4>
                                <hr />
                            </span>

                            <div>
                                <div align="center" class="col-md-12">
                                    @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"PAYMENT REPORT", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                                </div>
                                <div align="center" class="col-md-12 mt-3">
                                    @if(isset($getPaymentQuery) and $getPaymentQuery)
                                        <table class="table table-responsive table-condensed"> 
                                            <tr bgcolor="#f9f9f9" class="text-uppercase">
                                                <td><b>CLass Name:</b> {{ $className }}</td>
                                                <td><b>Term:</b> {{ $termName }}</td>
                                                <td><b>Session:</b> {{ $sessionName }}</td>
                                            </tr>
                                        </table>
                                    @endif
                                </div>

                                <div align="center" class="col-md-12">
                                    <table class="table table-stripped table-responsive table-condensed"> 
                                       
                                    </table>
                                </div>

                                <div align="center" class="col-md-12 mt-2">
                                    <table style="font-size:13px;" class="table table-stripped table-responsive table-condensed table-sm"> 
                                         <thead>
                                            <caption>
                                                <th colspan="12" class="text-center text-info">{{$paymentType}}</th>
                                            </caption>
                                            <tr style="background:#d9d9d9; font-size:12px;">
                                                <th>SN</th>
                                                <th>STUDENT</th>
                                                <th>REG. NO</th>
                                                <th>TOTAL&nbsp;PAID</th>
                                                <th>OUTSTANDING</th>
                                                <th>TOTAL&nbsp;DUE</th>
                                                <th>CLASS</th>
                                                <th>TERM</th>
                                                <th>PAYMENT&nbsp;DATE</th>
                                                <th>DATE</th>
                                                <th>TIME</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--STUDENT ADDITIONAL FEES-->
                                            @if(isset($getPaymentQuery) and $getPaymentQuery)
                                            @php $totalAmountToBePaid = 0; $totalAmountPaid = 0; $totalOutstanding = 0; @endphp
                                            @forelse($getPaymentQuery as $key=>$report)
                                            <tr>
                                                <td>{{ ($getPaymentQuery ? (($getPaymentQuery->currentpage()-1) * $getPaymentQuery->perpage() + (1 + $key)) : (1 + $key)) }}</td>
                                                <td class="text-info text-left">{{ ($report->studentName ? $report->studentName : $report->student_lastname .' '. $report->student_firstname) }}</td>
                                                <td class="text-info">{{ ($report->studentRegID ? $report->studentRegID : $report->student_regID) }}</td>
                                                <td class="text-success">{{ number_format($totalAmountPaidStudent[$key.$report->studentID], 2) }}</td>
                                                <td class="text-danger">{{ number_format(($outstandingStudent[$key.$report->studentID] ? $outstandingStudent[$key.$report->studentID] : ($totalAmountPaidStudent[$key.$report->studentID] ? $outstandingStudent[$key.$report->studentID] : $totalAmounttoBePaidByClass)), 2) }}</td>
                                                <td class="text-info">{{ number_format(($totalAmountToBePaidStudent[$key.$report->studentID] ? $totalAmountToBePaidStudent[$key.$report->studentID] : $totalAmounttoBePaidByClass), 2) }}</td>
                                                <td>{{ ($report->className ? $report->className : $report->class_name) }}</td>
                                                <td>{{ ($termID == 4 ? 'Session' : ($report->term_name ? $report->term_name : $termName)) }}</td>
                                                <td>{{ ($report->payment_date ? $report->payment_date : ' - ') }}</td>
                                                <td>{{ ($report->created_at ? $report->created_at : ' - ') }}</td>
                                                <td>{{ ($report->payment_time ? $report->payment_time : ' - ') }}</td>
                                            </tr>
                                            @php    
                                                $totalAmountPaid        += ($totalAmountPaidStudent[$key.$report->studentID]);  
                                                $totalAmountToBePaid    += ($totalAmountToBePaidStudent[$key.$report->studentID] ? $totalAmountToBePaidStudent[$key.$report->studentID] : $totalAmounttoBePaidByClass); 
                                                $totalOutstanding       += ($totalAmountPaidStudent[$key.$report->studentID] ? $outstandingStudent[$key.$report->studentID] : $totalAmounttoBePaidByClass);
                                            @endphp
                                            
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-danger"> 
                                                    <span class="d-print-none">No Report Found!</span> 
                                                </td>
                                            </tr>
                                        @endforelse
                                        
                                            <tr bgcolor="#f3f3f3">
                                                <td colspan="3" class="text-center text-success"><b>TOTAL:</b></td>
                                                <td class="text-center text-success"><b>{{number_format(($totalAmountPaid), 2)}}</b></td>
                                                <td class="text-center text-danger"><b>{{number_format(($totalOutstanding), 2)}}</b></td>
                                                <td class="text-center text-info"><b>{{number_format(($totalAmountToBePaid), 2)}}</b></td>
                                                <td colspan="5" class="text-right text-info"><b></b></td>
                                            </tr>
                                        
                                        @endif
                                    </thead>
                                    </table>
                                    @if(isset($getPaymentQuery) and $getPaymentQuery and ($getPaymentQuery->hasPages()))
                                    <div class="row d-print-none">
                                        <div align="right" class="col-xs-12 col-sm-12">
                                            Showing {{($getPaymentQuery->currentpage()-1)*$getPaymentQuery->perpage()+1}}
                                            to {{$getPaymentQuery->currentpage()*$getPaymentQuery->perpage()}}
                                            of  {{$getPaymentQuery->total()}} entries
                                            <br />
                                        <div class="hidden-print">{{ $getPaymentQuery->links() }}</div> 
                                        </div>
                                    </div>
                                    @endif 
                                <hr />
                            </div>
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

        //NOTE: This code is very VITAL - Select or diselect
        $("#studentName" ).prop('required', false);  
        $("#studentName" ).prop('disabled', true); 
        $("#studentNameSearch").hide();
        $("#paymentReportSearch").show();
        $("#paymentReport" ).val(1); 

    });//end document
</script>
<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
@if(Session::get("getStudentTypeToSearch") == 2)
    @include('PartialView.getSudentListWithClassIDForMark')
@else
    @include('PartialView.getSudentListWithClassID')
@endif
<!--End get subject-->
@endsection