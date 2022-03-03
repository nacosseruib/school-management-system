                                @if(Session::get('getSchoolProfile') and (Session::get('getSchoolProfile')->show_fee_breakdown <> 0) )
                                <div class="row" style="min-height:1450px;">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <span class="d-print-none">
                                                        <h4 class="card-title" id="from-actions-multiple">{{ __('FEE ENQUIRY') }}</h4>
                                                        <hr />
                                                    </span>
                                                    <div>
                                                        <div align="center" class="col-md-12 mb-3">
                                                            @include('PartialView.schoolHeaderNameLogo', ['titleText'=>" FEES ENQUIRY", 'showLogo'=>0, 'showSlogan'=>0, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
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
                                @endif