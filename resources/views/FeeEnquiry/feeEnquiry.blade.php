@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper('Class Fees Enquiry') }} @endsection
@section('studentFeeEnquiryHeaderTitle') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

          <div class="card d-print-none">
                    <div class="card-header">
                        @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                        <h4 class="card-title" id="from-actions-multiple">{{ __("SEARCH CLASS") }}</h4>
                        <hr />
                        <form class="form" method="post" action="{{route('searchClassFeeEnquiry')}}">
                        @csrf
                           <div class="row offset-md-1">
                                        <div class="col-md-6 mt-2">
                                            <label> {{ __('Select Class') }} <b class="text-danger">*</b> </label>
                                            <select class="form-control" required name="className" id="getClassID">
                                                <option value=""> Select Class </option>
                                                @if(isset($allClass))
                                                    @forelse($allClass as $class)
                                                        <option value="{{ $class->classID }}" {{($classID == $class->classID ? 'selected' : '') }}>{{ __($class->class_name) }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @if ($errors->has('className'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('className') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label> {{ __('Term') }} <b class="text-danger">*</b> </label> 
                                            <select class="form-control" required name="schoolTerm" id="schoolTerm">
                                                <option value=""> Select Term </option>
                                                @if(isset($schoolTerm))
                                                    @forelse($schoolTerm as $term)
                                                        <option value="{{ $term->termID }}" {{($termID == $term->termID ? 'selected' : '') }} >{{($term->term_name) }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @if ($errors->has('schoolTerm'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolTerm') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                            </div><!--//row-->
                             <div class="card-body">
                                        <div class="px-3">
                                            <div class="form-actions top clearfix">
                                                <div class="buttons-group text-center">
                                                    <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                        <i class="fa fa-search"></i> {{ __('Search') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                        </form>
                </div>
            </div>
           
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="d-print-none">
                                <h4 class="card-title" id="from-actions-multiple">{{ __('FEE ENQUIRY') }}</h4>
                                <hr />
                            </span>
                            <div>
                                <div align="center" class="col-md-12 mb-3">
                                    @include('PartialView.schoolHeaderNameLogo', ['titleText'=>" FEES ENQUIRY", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                                </div>
                           
                                <div align="center" class="col-md-12">
                                        <table class="table table-responsive table-condensed"> 
                                            <tr bgcolor="#f9f9f9">
                                                <td><b>CLass Name:</b> {{ $classNameValue}}</td>
                                                <td><b>Term:</b> {{ $termName}}</td>
                                                <td><b>Session:</b> {{ $schoolSession}}</td>
                                            </tr>
                                        </table>
                                </div>
                                <div align="center" class="col-md-12">
                                    <table class="table table-stripped"> 
                                    <thead>
                                        <tr style="background:#d9d9d9">
                                            <th>{{ ('S/N') }}</th>
                                            <th>{!! ("Fee's&nbsp;Name") !!}</th>
                                            <th>&#8358; {{'Fee Amount'}}</th>
                                            <th>{!! ("Fee's&nbsp;Duration") !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--CORE FEES -->
                                        @if(isset($getAllAssignedCoreFees))
                                            @php $serialNo = 1; $subTotalAmountCoreFee = 0; $originalTotalAmountCoreFee =0; @endphp
                                            <tr>
                                                <td colspan="4" class="text-center text-info">CLASS CORE FEES</td>
                                            </tr>
                                            @forelse($getAllAssignedCoreFees as $key=>$listCoreFee)
                                                <tr>
                                                    <td>{{($serialNo ++)}}</td>
                                                    <td class="text-success text-left"><b>{{ $listCoreFee->fees_name}}</b></td>
                                                    <th class="text-left"><b>{{number_format($listCoreFee->amount, 2)}}</b></th>
                                                    <td class="text-left"> 
                                                        {{ ($listCoreFee->fees_occurent_type == 1 ? '1st Term Only' : '') . ($listCoreFee->fees_occurent_type == 2 ? '2nd Term Only' : '')  . ($listCoreFee->fees_occurent_type ==3 ? '3rd Term Only' : '') . ($listCoreFee->fees_occurent_type == 4 ? 'Per Session Only' : '') }} 
                                                    </td>
                                                </tr>
                                                @php 
                                                    $originalTotalAmountCoreFee += $listCoreFee->amount;
                                                @endphp
                                            @empty
                                            @endforelse
                                            <tr bgcolor="#d0d0d0">
                                                <td colspan="2" class="text-center text-info"><b>SUB-TOTAL:</b></td>
                                                <td class="text-left text-info"><b>&#8358;{{number_format(($originalTotalAmountCoreFee), 2)}}</b></td>
                                                <td></td>
                                            </tr>
                                        @endif


                                        <!--PERIODIC FEES -->
                                        @if(isset($getAllAssignedFees))
                                        @php $subTotalAmountFee = 0; $originalTotalAmountFee = 0; @endphp
                                        <tr>
                                            <td colspan="4" class="text-center text-info">CLASS PERIODIC FEES</td>
                                        </tr>
                                        @forelse($getAllAssignedFees as $keyFee=>$listFee)
                                        <tr>
                                            <td>{{$serialNo ++}}</td>
                                            <td class="text-success text-left"><b>{{ $listFee->fees_name}}</b></td>
                                            <th class="text-left"><b>{{number_format($listFee->amount, 2)}}</b></th>
                                            <td class="text-left"> 
                                                {{ ($listFee->fees_occurent_type ==1 ? '1st Term Only' : '') . ($listFee->fees_occurent_type ==2 ? '2nd Term Only' : '')  . ($listFee->fees_occurent_type ==3 ? '3rd Term Only' : '') . ($listFee->fees_occurent_type ==4 ? 'Per Session Only' : '') }} 
                                            </td>
                                        </tr>
                                        @php
                                            $originalTotalAmountFee += $listFee->amount;
                                        @endphp
                                        @empty
                                            
                                        @endforelse
                                        <tr bgcolor="#d0d0d0"> 
                                            <td colspan="2" class="text-center text-info"><b>SUB-TOTAL:</b></td>
                                            <td class="text-left text-info"><b>&#8358;{{number_format(($originalTotalAmountFee), 2)}}</b></td>
                                            <td></td>
                                        </tr>
                                        
                                        <tr  bgcolor="black">
                                            <td colspan="2" class="text-center text-danger"><b>TOTAL FEES:</b></td>
                                            <td class="text-left text-danger"><b><b>&#8358;{{number_format(($originalTotalAmountCoreFee + $originalTotalAmountFee ), 2)}}</b></b></td>
                                            <td></td>
                                        </tr>
                                       
                                        @endif
                                    </thead>
                                    </table> 
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

        //NOT USE FOR NOW: Select or diselect
        /*$(".getClassFeeItem" ).click(function() { 
            var getItemID = this.id; 
            var getID = getItemID.replace('item', '');
            if($(this).prop("checked")) { 
                $('#' + getID).val(1);
                $('#getFeeAndClassID' + getID).val(getID);
            }else{ 
                $('#' + getID).val(0);
                $('#getFeeAndClassID' + getID).val(getID);
            }
        });*/

        //Submit Core Edit Fee Form
        $(".submitEditFormBtn").click(function() { 
            var getFeeID = this.id; 
            var newAmount = $('#newFeeAmount' + getFeeID).val();
            var studentID = {{ ((isset($studentDetails) and $studentDetails) ? $studentDetails->studentID : 0) }};
            var classID = {{ ((isset($studentDetails) and $studentDetails) ? $studentDetails->classID : 0) }};
           if(getFeeID)
           {
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
           }
        }); 
        

        //Submit Adding More Fee for Student
        $(".submitMoreFee").click(function() { 
            if($("#feeName").val() == ''){
                alert("Please select the fee you want to add for this student!");
                return false;
            }
            $("#addMoreFeeForm").submit();
        }); 

        
    });//end document
</script>

@endsection