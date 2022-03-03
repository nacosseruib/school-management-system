@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper('Student Payment Receipt') }} @endsection
@section('studentFeeSetupHeaderTitle') active @endsection
@section('content')

    <div class="main-content">
         <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border: 10px dotted grey;">
                        <div class="card-header" style="border: 15px dotted purple;">
                            
                            <div class="row">
                                <div class="col-md-7 col-sm-12">
                                    @include('PartialView.schoolHeaderLogoReceipt', ['titleText'=>"Student Payment Receipt", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>0, 'showHeader'=>1])
                                </div>
                                <div class="col-md-5 col-sm-12">
                                    <div class="mb-3 text-center">
                                        <span class="text-success"><b>PAYMENT RECEIPT</b></span>
                                    </div>
                                    <div class="mb-3 text-center text-info">
                                        <u><i>PAYMENT DATE: {{ ($getDetails ? $getDetails->payment_date : '') }}</i></u>
                                    </div>
                                    <div class="mb-3 text-center text-info">
                                        <i>TRANSACTION NO.: {{ ($getDetails ? $getDetails->transactionID : '') }}</i>
                                    </div>
                                </div>
                            </div>
                            
                            <table class="table table-bordered table-responsive">
                                <tr>
                                </tr>
                            </table>
                            
                            <div class="row" style="background: #f9f9f9;">
                                <div class="col-sm-7">
                                    <table class="table table-striped" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>STUDENT NAME</td>
                                            <td>{{ ($getDetails ? $getDetails->studentName : '') }}</td>
                                        </tr>
                                        <tr>
                                            <td>REGISTRATION NO.</td>
                                            <td>{{ ($getDetails ? $getDetails->studentRegID : '') }}</td>
                                        </tr>
                                        <tr>
                                            <td>CLASS</td>
                                            <td>{{ ($getDetails ? $getDetails->class_name : '') }}</td>
                                        </tr>
                                        <tr>
                                            <td>TERM</td>
                                            <td>{{ ($getDetails ? $getDetails->term_name : '') }}</td>
                                        </tr>
                                        <tr>
                                            <td>SESSION</td>
                                            <td>{{ ($getDetails ? $getDetails->session_code : '') }}</td>
                                        </tr>
                                   </table>
                                </div>
                                <div class="col-sm-5">
                                    <div col-md-12> <i class="fa fa-asterisk text-info"></i> <span class="text-dark">Payment Description: {{ ($getDetails ? $getDetails->payment_description : '') }}</span></div>
                                      <br />
                                    <div col-md-12><i class="fa fa-asterisk text-info"></i> <span class="text-dark">AMOUNT PAID: &#8358;{{ ($getDetails ? number_format($getDetails->amount_paid, 2) : 0.00) }} </span></div>
                                </div>
                            </div>
                            
                            <div align="center">
                                <table class="table table-bordered table-striped table-responsive">
                                    <tr class="text-white text-center bg-blue">
                                        <th>SN</th>
                                        <th>FEE NAME</th>
                                        <th>DESCRIPTION</th>
                                        <th>&#8358; AMOUNT</th>
                                    </tr>
                                    @php $serialNo = 1; $totalCore = 0; $totalTerm = 0; $totalAdditional = 0;  @endphp
                                    @if($getSessionCoreFees)
                                        <tr class="text-info text-center">
                                            <td colspan="4">CORE FEES</td>
                                        </tr>
                                        @foreach($getSessionCoreFees as $coreKey => $listCore)
                                        <tr class="text-left">
                                            <td width="5" class="text-center">{{ $serialNo ++}}</td>
                                            <td>{{ ($listCore->feesNameHistory <> '' ? $listCore->feesNameHistory : $listCore->fees_name) }}</td>
                                            <td>{{ $listCore->fees_description }}</td>
                                            <td>{{ number_format($coreAmount[$coreKey], 2) }}</td>
                                        </tr>
                                        @php $totalCore += $coreAmount[$coreKey]; @endphp
                                        @endforeach
                                    @endif
                                    
                                    
                                    @if($getTermFees)
                                        <tr class="text-info text-center">
                                            <td colspan="4">TERM FEES</td>
                                        </tr>
                                        @foreach($getTermFees as $termKey => $listTerm)
                                        <tr class="text-left">
                                            <td width="5" class="text-center">{{ $serialNo ++ }}</td>
                                            <td>{{ ($listTerm->feesNameHistory <> '' ? $listTerm->feesNameHistory : $listTerm->fees_name) }}</td>
                                            <td>{{ $listTerm->fees_description }}</td>
                                            <td>{{ number_format($termAmount[$termKey], 2) }}</td>
                                        </tr>
                                        @php $totalTerm += $termAmount[$termKey]; @endphp
                                        @endforeach
                                    @endif
                                    
                                    
                                    @if($getAdditionalFees)
                                        <tr class="text-info text-center">
                                            <td colspan="4">ADDITIONAL FEES</td>
                                        </tr>
                                        @foreach($getAdditionalFees as $listMore)
                                        <tr class="text-left">
                                            <td width="5" class="text-center">{{ $serialNo ++ }}</td>
                                            <td>{{ ($listMore->studentFeesName <> '' ? $listMore->studentFeesName : $listMore->fees_name) }}</td>
                                            <td>{{ $listMore->fees_description }}</td>
                                            <td>{{ number_format(($listMore->studentAmount <> '' ? $listMore->studentAmount : $listMore->feesSetupAmount) , 2) }}</td>
                                        </tr>
                                        @php $totalAdditional += ($listMore->studentAmount <> '' ? $listMore->studentAmount : $listMore->feesSetupAmount); @endphp
                                        @endforeach
                                    @endif
                                    <tr class="text-info text-right">
                                        <td colspan="3" class="bg-blue text-white">SUB-TOTAL AMOUNT DUE</td>
                                        <td class="bg-blue text-white">&#8358; {{ number_format($totalCore + $totalTerm + $totalAdditional, 2) }}</td>
                                    </tr>
                                    <tr class="text-warning text-right bg-light">
                                        <td colspan="3">TOTAL PREVIOUS BALANCE</td>
                                        <td> &#8358;{{ ($getDetails ? number_format((($getDetails->total_amount_due) - ($totalCore + $totalTerm + $totalAdditional)), 2) : 0.00) }} </td>
                                    </tr>
                                    <tr class="text-info text-right bg-light">
                                        <td colspan="3">TOTAL AMOUNT DUE</td>
                                        <td> &#8358;{{ ($getDetails ? number_format(($getDetails->total_amount_due), 2) : 0.00) }}</td>
                                    </tr>
                                    <tr class="text-left">
                                         <td colspan="4">
                                            <table class="table table-bordered table-striped table-responsive">
                                                <tr class="text-left">
                                                    <td colspan="4">Payment Summary</td>
                                                </tr> 
                                                <tr class="text-left">
                                                    <td class="text-info"><small>TOTAL AMOUNT DUE: </small></td>
                                                    <td colspan="3"> &#8358;{{ ($getDetails ? number_format(($getDetails->total_amount_due), 2) : 0.00) }} - <small><i> <span id="totalAmountDueWord"></span> </i></small></td>
                                                </tr>
                                                <tr class="text-left">
                                                    <td class="text-success"><small>AMOUNT PAID: </small></td>
                                                    <td colspan="3">  &#8358;{{ ($getDetails ? number_format($getDetails->amount_paid, 2) : 0.00) }} - <small><i> <span id="amountPaidWord"></span> </i></small></td>
                                                </tr>
                                                <tr class="text-left">
                                                    <td class="text-danger"><small>TOTAL OUTSTANDING BAL.: </small></td>
                                                    <td colspan="3">  &#8358;{{ ($getDetails ? number_format($getDetails->balance_due, 2) : 0.00) }} - <small><i> <span id="totalOutstandingWord"></span> </i></small></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end content-wrapper-->
    </div><!--end main content-->   

@endsection

@section('styles')
    <style type="text/css">
        table tr th, table tr td{
            border: 1px solid #666 !important;
        }
        table tr th, table tr td{
            padding: 3px !important; /* Apply cell padding */
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript"> 
        
        var totalAmountDue      = "{{ number_format($getDetails->total_amount_due, 2) }}";
        var totalAmountDueMoney = totalAmountDue.split('.');
        
        var amountPaid          = "{{ ($getDetails ? number_format($getDetails->amount_paid, 2) : 0.00) }}";
        var amountPaidMoney     = amountPaid.split('.');
        
        var totalBalance        = "{{ ($getDetails ? number_format($getDetails->balance_due, 2) : 0.00) }}";
         var totalBalanceMoney  = totalBalance.split('.');
         
        function lookup()
        {
        	//Convert amount to words
        	//Total Amount Due
           	var words;
            var naira = totalAmountDueMoney[0];
            var kobo = totalAmountDueMoney[1];            
            var word1 = toWords(naira)+"naira only";
            var word2 = ", "+toWords(kobo)+" kobo only";
            if(kobo != "00")
                words = word1 + word2;
            else
                words = word1;
                var getWord = words.toUpperCase();
    			var parternRule1 = /HUNDRED AND NAIRA ONLY/ig;
    			var parternRule2 = /HUNDRED AND THOUSAND NAIRA ONLY/g;
    			var instance1 = parternRule1.test(getWord);
    			var instance2 = parternRule2.test(getWord);
    			if((instance1))
    			{
    			 	document.getElementById('totalAmountDueWord').innerHTML = getWord.replace(parternRule1, ' HUNDRED NAIRA ONLY ');
    			}else if((instance2))
    			{
    			 	document.getElementById('totalAmountDueWord').innerHTML = getWord.replace(parternRule2, ' HUNDRED THOUSAND NAIRA ONLY ');
    			}else
    			{
    			  	document.getElementById('totalAmountDueWord').innerHTML = getWord;
    			}
            //document.getElementById('totalAmountDueWord').innerHTML = words; //words.toUpperCase();
            //Amount Paid
            var words;
            var naira = amountPaidMoney[0];
            var kobo = amountPaidMoney[1];            
            var word1 = toWords(naira)+"naira only";
            var word2 = ", "+toWords(kobo)+" kobo only";
            if(kobo != "00")
                words = word1 + word2;
            else
                words = word1;
                var getWord = words.toUpperCase();
    			var parternRule1 = /HUNDRED AND NAIRA ONLY/ig;
    			var parternRule2 = /HUNDRED AND THOUSAND NAIRA ONLY/g;
    			var instance1 = parternRule1.test(getWord);
    			var instance2 = parternRule2.test(getWord);
    			if((instance1))
    			{
    			 	document.getElementById('amountPaidWord').innerHTML = getWord.replace(parternRule1, ' HUNDRED NAIRA ONLY ');
    			}else if((instance2))
    			{
    			 	document.getElementById('amountPaidWord').innerHTML = getWord.replace(parternRule2, ' HUNDRED THOUSAND NAIRA ONLY ');
    			}else
    			{
    			  	document.getElementById('amountPaidWord').innerHTML = getWord;
    			}
            //document.getElementById('amountPaidWord').innerHTML = words;
            //Total Outstanding
            var words;
            var naira = totalBalanceMoney[0];
            var kobo = totalBalanceMoney[1];            
            var word1 = toWords(naira)+"naira only";
            var word2 = ", "+toWords(kobo)+" kobo only";
            if(kobo != "00")
                words = word1 + word2;
            else
                words = word1;
                
                 var getWord = words.toUpperCase();
    			 var parternRule1 = /HUNDRED AND NAIRA ONLY/ig;
    			 var parternRule2 = /HUNDRED AND THOUSAND NAIRA ONLY/g;
    			 var instance1 = parternRule1.test(getWord);
    			 var instance2 = parternRule2.test(getWord);
    			 if((instance1))
    			 {
    			 	document.getElementById('totalOutstandingWord').innerHTML = getWord.replace(parternRule1, ' HUNDRED NAIRA ONLY ');
    			 }else if((instance2))
    			 {
    			 	document.getElementById('totalOutstandingWord').innerHTML = getWord.replace(parternRule2, ' HUNDRED THOUSAND NAIRA ONLY ');
    			 }else
    			 {
    			  	document.getElementById('totalOutstandingWord').innerHTML = getWord;
    			 }
                //document.getElementById('totalOutstandingWord').innerHTML = words;
            
        }//
        $('#totalAmountDueWord').css('textTransform', 'capitalize').css('font-size', 12);
        $('#amountPaidWord').css('textTransform', 'capitalize').css('font-size', 12);
        $('#totalOutstandingWord').css('textTransform', 'capitalize').css('font-size', 12);
		//
    </script>
@endsection
