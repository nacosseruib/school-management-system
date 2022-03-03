@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper('Student Fees Payment') }} @endsection
@section('studentPaymentFeeHeaderTitle') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

                <div class="card d-print-none">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-multiple">{{ __("SEARCH STUDENTS") }}</h4>
                        <hr />
                        
                        <form class="form" method="post" action="{{route('searchStudentFeePayment')}}">
                        @csrf
                            @include('PartialView.searchCurrentFormerStudent')
                        </form>
                        
                    </div>
                </div>
           
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="d-print-none">
                                <h4 class="card-title" id="from-actions-multiple">{{ __('STUDENT FEES PAYMENT') }}</h4>
                                <hr />
                            </span>
                            <div>
                                <div align="center" class="col-md-12">
                                @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"Student Payment History", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                                </div>
                                <div align="center" class="col-md-12">
                                    @if(isset($studentDetails) and $studentDetails)
                                        <table class="table table-responsive table-condensed"> 
                                            <tr bgcolor="#f9f9f9">
                                                <th colspan="3" class="text-uppercase text-success text-center">
                                                    <h4><b>{{ $studentDetails->student_lastname .' '. $studentDetails->student_firstname }}</b> </h4>
                                                </th> 
                                                <th rowspan="3">
                                                    @if($studentDetails->photo) <img alt=" " width="90" height="100" src="{{asset($studentImagePath . $studentDetails->photo)}}" class="img-responsive mt-2" /> @endif
                                                </th>
                                            </tr>
                                            <tr bgcolor="#f9f9f9">
                                                <td><b>Reg. ID:</b> {{ $studentDetails->student_regID}}</td>
                                                <td><b>Gender:</b> {{ $studentDetails->student_gender}}</td>
                                                <td><b>ROll No:</b> {{ $studentDetails->student_roll}}</td>
                                            </tr>
                                            <tr bgcolor="#f9f9f9">
                                                <td><b>CLass Name:</b> {{ $studentDetails->class_name}}</td>
                                                <td><b>Term:</b> {{ $termName}}</td>
                                                <td><b>Session:</b> {{ $schoolSession}}</td>
                                            </tr>
                                        </table>
                                    @endif
                                </div>

                                <div align="center" class="col-md-12">
                                    <table class="table table-stripped table-responsive table-condensed"> 
                                        <tr class="bg-black">
                                            <td class="text-success">TOTAL AMOUNT PAID: <b>{{number_format(($totalAmountPaid), 2)}}</b> </td>
                                            <td class="text-danger">PREV. DEBT BAL.: <b>{{number_format(($totalPreviousDue), 2)}}</b> </td>
                                            <td class="text-danger">TOTAL OUTSTANDING: <b>{{number_format(($totalBalanceDue), 2)}}</b> </td>
                                            <td class="text-info">TOTAL AMOUNT DUE: <b>{{number_format(($totalAmountDueToBePaidPerStudent), 2)}}</b> </td>
                                        </tr>
                                    </table>
                                </div>

                                @if(isset($studentDetails) and $studentDetails)
                                <form class="form" id="addMoreFeeForm" method="post" action="{{route('processStudentFeePayment')}}">
                                @csrf
                                <div class="col-md-12 d-print-none pt-2 mt-2" style="background:#f3f3f3">
                                    <div class="row mt-2 d-print-none">
                                        <div class="col-md-12 mb-2">
                                            <div class="text-info text-center">
                                                <b>MAKE PAYMENT</b>
                                            </div>
                                        </div>
                                    </div><!--//row-->

                                    <div class="row offset-md-1 pt-2 d-print-none">
                                        @if(Session::get('schoolTerm') == 4)
                                            <div class="col-md-11">
                                                <div class="form-control text-warning text-center">Please, select 1st, 2nd or 3rd Term from the list above to make payment !</div>
                                            </div>
                                        @else
                                            <div class="col-md-3">
                                                <label>Amount To Pay <b class="text-danger">*</b> </label>
                                                <input type="text" value="{{old('amountToPay')}}" class="form-control" required name="amountToPay" placeholder="Enter Amount To Pay">
                                            </div>
                                            <div class="col-md-5">
                                                <label>Payment Description (Optional)</label>
                                                <input type="text" value="{{old('paymentDescription')}}" class="form-control" required maxlength="41" name="paymentDescription" placeholder="Being further part payment...">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Payment Date <b class="text-danger">*</b> </label>
                                                <input type="date" class="form-control" value="{{old('paymentDate')}}" onKeyDown="return false;" maxlength="10" name="paymentDate" placeholder="Select Payment Date (Optional)">
                                                <!--date('dS l M, Y')-->
                                            </div>
                                        @endif
                                    </div><!--//row-->

                                    <div class="col-md-12 text-center mt-3">
                                        @if(Session::get('schoolTerm') <> 4)
                                        <button type="button" data-toggle="modal" data-backdrop="false" data-target="#addMoreFee" class="btn btn-raised btn-primary btn-sm mr-1">
                                            <i class="fa fa-save"></i> Make Payment
                                        </button>
                                        @endif
                                    </div> 
                                    <!-- Confirming Add Fees  Modal -->
                                    <div class="modal fade text-left" id="addMoreFee" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info white">
                                                    <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-save"></i> {{ __('Make Fee Payment')}}  </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">  {{ __('Your sure you want to continue with this payment ?') }} </div>
                                                    <p>
                                                        <div class="text-success text-center"> {{ __("This amount will be added to this student payment history and SMS with be sent if student has parent's phone number.")}} </div>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                    <button type="submit" class="btn btn-outline-success submitMoreFee">{{ __('Pay Now') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end Confirming Adding Fee Modal-->
                                </div>
                                </form>
                                @endif



                                <div align="center" class="col-md-12 mt-3">
                                    <table style="font-size:13px;" class="table table-stripped table-responsive table-condensed table-sm"> 
                                         <thead>
                                            <caption>
                                                <th colspan="12" class="text-center text-info">PAYMENT HISTORY</th>
                                            </caption>
                                            <tr style="background:#d9d9d9; font-size:12px;">
                                                <th>SN</th>
                                                <th>AMOUNT&nbsp;PAID</th>
                                                <th>OUTSTANDING</th>
                                                <th>TOTAL&nbsp;DUE</th>
                                                <th>DESCRIPTION</th>
                                                <th>SESSION</th>
                                                <th>TERM</th>
                                                <th>PAYMENT&nbsp;DATE</th>
                                                <th>DATE</th>
                                                <th>TIME</th>
                                                <th>PAID&nbsp;BY</th>
                                                <th colspan="2" class="d-print-none"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--STUDENT ADDITIONAL FEES-->
                                            @if(isset($allPaymentHistory))
                                            @php $totalAmountPaidSUM = 0; @endphp
                                            @forelse($allPaymentHistory as $key=>$history)
                                            <tr>
                                                <td>{{ ($allPaymentHistory->currentpage()-1) * $allPaymentHistory->perpage() + (1 + $key ++) }}</td>
                                                <td class="text-success"><b>{{ number_format($history->amount_paid, 2) }}</b></td>
                                                <td class="text-danger"><b>{{ number_format($history->balance_due, 2) }}</b></td>
                                                <td class="text-info"><b>{{ number_format($history->total_amount_due, 2) }}</b></td>
                                                <td class="text-left">{{ $history->payment_description }}</td>
                                                <td class="text-left">{{ $history->session_code }}</td>
                                                <td>{{ $history->term_name }}</td>
                                                <td>{{ $history->payment_date }}</td>
                                                <td>{{ $history->created_at }}</td>
                                                <td>{{ $history->payment_time }}</td>
                                                <td class="text-left">{{ $history->paid_by_name }}</td>
                                                <td class="d-print-none">
                                                    <a href="{{ route('studentPaymentReceipt', ['transactionID'=>$history->transactionID]) }}" class="btn btn-default btn-sm"><i class="fa fa-print fa-2x"></i></a>
                                                </td>
                                                <td class="d-print-none">
                                                    <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#removePayment{{$history->paymentfeesID}}" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @php 
                                                $totalAmountPaidSUM += $history->amount_paid; 
                                            @endphp
                                            <!-- Delete Payment Fee From History Modal -->
                                            <div class="modal fade text-left" id="removePayment{{$history->paymentfeesID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger white">
                                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Payment History')}}  </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">  {{ 'Delete permanantly: ' . number_format($history->amount_paid, 2) .' - '. $history->payment_description }} </div>
                                                            <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this payment from student history?')}} </h5>
                                                            <p>
                                                                <div class="text-danger text-center"> {{ __('This payment will be deleted from this student payment history !')}} </div>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                            <a href="{{ route('deletePaymentHistory', ['ID'=>$history->paymentfeesID])}}" class="btn btn-outline-danger">{{ __('Delete Now') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end Modal-->
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center text-danger"> 
                                                    <span class="d-print-none">No Payment Histotry !</span> 
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr bgcolor="#d0d0d0">
                                            <td colspan="4" class="text-center text-success"><b>TOTAL AMOUNT PAID:</b></td>
                                            <td class="text-left text-success"><b>{{number_format(($totalAmountPaidSUM), 2)}}</b></td>
                                            <td colspan="2" class="text-center text-danger"><b>TOTAL OUTSTANDING:</b></td>
                                            <td class="text-left text-danger"><b>{{number_format(($totalBalanceDue), 2)}}</b></td>
                                            <td colspan="2" class="text-right text-info"><b>TOTAL AMOUNT DUE:</b></td>
                                            <td class="text-left text-info"><b>{{number_format(($totalAmountDueToBePaidPerStudent), 2)}}</b></td>
                                            <td colspan="2" class="d-print-none"></td>
                                        </tr>
                                        @endif
                                    </thead>
                                    </table>
                                    <div class="row d-print-none">
                                        <div align="right" class="col-xs-12 col-sm-12">
                                            Showing {{($allPaymentHistory->currentpage()-1)*$allPaymentHistory->perpage()+1}}
                                            to {{$allPaymentHistory->currentpage()*$allPaymentHistory->perpage()}}
                                            of  {{$allPaymentHistory->total()}} entries
                                            <br />
                                        <div class="hidden-print">{{ $allPaymentHistory->links() }}</div> 
                                        </div>
                                    </div> 
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
        $("#studentName" ).prop('required', true);  
        $("#studentName" ).prop('disabled', false); 
        $("#studentNameSearch").show();
        $("#reportType" ).prop('disabled', true); 
        $("#reportType").prop('required', false);  
        $("#paymentReportSearch").hide();
        $("#paymentReport" ).val(0); 

    });//end document
</script>

<script>
    $(document).ready(function(){
        //Select or diselect
        $(".getClassFeeItem" ).click(function() { 
            var getItemID = this.id; 
            var getID = getItemID.replace('item', '');
            if($(this).prop("checked")) { 
                $('#' + getID).val(1);
                $('#getFeeAndClassID' + getID).val(getID);
            }else{ 
                $('#' + getID).val(0);
                $('#getFeeAndClassID' + getID).val(getID);
            }
        });

        //Submit Core Edit Form
        $(".submitEditFormBtn").click(function() { 
            var getFeeID = this.id; 
            var newAmount = $('#newFeeAmount' + getFeeID).val();
            var studentID = {{ ((isset($studentDetails) and $studentDetails) ? $studentDetails->studentID : 0) }};
            var classID = {{ ((isset($studentDetails) and $studentDetails) ? $studentDetails->classID : 0) }};
           
            $.ajax({
                url: '{{url("/")}}' + '/update-student-fee-setup',
                type: "post",
                data: {'feeID': getFeeID, 'amount': newAmount, 'studentID': studentID, 'classID': classID, '_token': $('input[name=_token]').val()},
                success: function(data){
                    alert(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Internal/Internet error occurred! Looks like your session has expired or you are not connected to the internet.');
                }
            });
        }); 

        //Submit Adding More Fee for Student
        $(".submitMoreFee").click(function() { 
            $("#addMoreFeeForm").submit();
        }); 

        
    });//end document
</script>
<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
@include('PartialView.getSudentListWithClassIDForMark')
@include('PartialView.getSudentListWithClassID')
<!--End get subject-->
@endsection