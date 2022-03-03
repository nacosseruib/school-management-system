                        <!--//end Result Recap and GPA used-->
                        <div class="row">
                            <div class="col-sm-7" style="border-right: 1px solid #ddd;">
                                <span class="text-info">{{ strtoupper('Report Sheet Summary') }}</span><hr  class="mb-0 mt-0">
                                <div class="row">
                                    <div class="col-sm-8 font4">TOTAL MARK OBTAINABLE</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($totalMarkObtainable ? number_format(($totalMarkObtainable), 2, '.', ',') : ' - ') }}</b> </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 font4">TOTAL MARK OBTAINED</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($totalMarkObtained ? number_format(($totalMarkObtained), 2, '.', ',') : ' - ') }} </b></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 font4">CUMMULATIVE (%)</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($cummulativePercentage ? number_format(($cummulativePercentage), 2, '.', ',') .'%' : ' - ') }} </b></div>
                                </div>
                                <div class="row" style="color: @if($cummulativePercentage > 74.9) green @elseif($cummulativePercentage > 39.9) black @else red @endif;">
                                    <span class="col-sm-8 font4">OVERALL GRADE</span><br />
                                    <span class="col-sm-4 font4"> <b> {{ ($overAllGrade ? strtoupper($overAllGrade) : ' - ') }}</b></span>
                                </div>
                                <div class="row" style="color: @if($cummulativePercentage > 74.9) green @elseif($cummulativePercentage > 39.9) black @else red @endif;">
                                    <span class="col-sm-8 font4">CUMMULATIVE REMARK </span><br />
                                    <span class="col-sm-4 font4"><b> {{ ($cummulativeRemark ? strtoupper($cummulativeRemark) : ' - ') }} </b> </span>
                                </div>
                            </div>
                            <div class="col-sm-5" style="border-right: 1px solid #ddd; display:{{($termID == 1) ? 'none' : 'display'}}">
                                <span class="text-info">{{ strtoupper('Previous Report Sheet Summary') }}</span><hr  class="mb-0 mt-0">
                                <div class="row">
                                    <div class="col-sm-8 font4">FIRST TERM CUMMULATIVE</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($cummulativePercentage1st ? number_format(($cummulativePercentage1st), 2, '.', ',') .'%' : ' - ') }}</b> </div>
                                </div>
                                @if($termID == 2)
                                <div class="row">
                                    <div class="col-sm-8 font4">SECOND TERM CUMMULATIVE</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($cummulativePercentage2nd ? number_format(($cummulativePercentage2nd), 2, '.', ',') .'%' : ' - ') }} </b></div>
                                </div>
                                @endif
                                @if($termID == 3)
                                <div class="row">
                                    <div class="col-sm-8 font4">SECOND TERM CUMMULATIVE</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($cummulativePercentage2nd ? number_format(($cummulativePercentage2nd), 2, '.', ',') .'%' : ' - ') }} </b></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 font4">THIRD TERM CUMMULATIVE</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($cummulativePercentage3rd ? number_format(($cummulativePercentage3rd), 2, '.', ',') .'%' : ' - ') }} </b></div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-8 font4">SESSION AVERAGE CUMMULATIVE</div>
                                    <div class="col-sm-4 font4"> <b>{{ ($cummulativeSessionAverage ? number_format(($cummulativeSessionAverage), 2, '.', ',') .'%' : ' - ') }} </b></div>
                                </div>
                            </div>
                        </div>
                        <!--//end Result Recap and GPA used-->