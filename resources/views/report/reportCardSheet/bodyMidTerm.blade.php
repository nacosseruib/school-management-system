                        <div class="card-body">
                                  <div class="card-body">
                                      <table class="mainTable table_morecondensed table table-bordered table-striped table-hover table-responsive table-condensed">
                                         <thead>
                                            <tr style="font-size:14px; background:#f9f9f9; color:purple;">
                                                <th class="text-center">SN</th>
                                                <th class="text-center">SUBJECT</th>
                                                <th class="text-center">1ST&nbsp;CA<br />(20)</th>
                                                <th class="text-center">PERCENT<br />SCORE(%)</th>
                                                <th class="text-center">GRADE</th>
                                                <th class="text-center">REMARK</th>
                                                @if($_SERVER["HTTP_HOST"] == "kingston.schooleportal.com")
                                                <!--<th class="text-center">Position</th>-->
                                                @else
                                                <th class="text-center">POSITION</th>
                                                @endif
                                                <th class="text-center">SIGNATURE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($allSubject)
                                        @php 
                                            $numberFormatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);
                                        @endphp
                                        @forelse($allSubject as $keyStd=>$listResult)
                                            <tr class="text-center" style="font-size:14px; color: @if($markPercentageMidTerm[$keyStd.$student->studentID] > 74.9) green @elseif($markPercentageMidTerm[$keyStd.$student->studentID] > 39.9) black @else red @endif;">
                                                
                                                <td style="color: {!! ($markPercentageMidTerm[$keyStd.$student->studentID] < 40) ? 'red' : 'purple' !!};">
                                                    {{ (1 + $keyStd) }}
                                                </td>
                                                
                                                <!--subject-->
                                                <td style="color: {!! ($markPercentageMidTerm[$keyStd.$student->studentID] < 40) ? 'red' : 'purple' !!};" class="text-left">
                                                    {{ $listResult->subject_name}}
                                                </td>
                                                
                                                <td>
                                                    <!--Test 1-->
                                                     <b>{!! (($markTest1[$keyStd.$student->studentID]) ? $markTest1[$keyStd.$student->studentID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}</b> 
                                                </td>
                                                <td>
                                                    <!--% Score-->
                                                    <b>{!! (($markPercentageMidTerm[$keyStd.$student->studentID]) ? $markPercentageMidTerm[$keyStd.$student->studentID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!} </b> 
                                                </td>
                                                <td>
                                                    <!--Grade-->
                                                    <b>{!! (($markGradeMidTerm[$keyStd.$student->studentID]) ? $markGradeMidTerm[$keyStd.$student->studentID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}</b>   
                                                </td>
                                                <td>
                                                    <!--Remark-->
                                                    {!! (($markRemarkMidTerm[$keyStd.$student->studentID]) ? $markRemarkMidTerm[$keyStd.$student->studentID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                </td>
                                                @if($_SERVER["HTTP_HOST"] == "kingston.schooleportal.com")
                                                
                                                @else
                                                <td>
                                                    <!--Position-->
                                                    {!! (($markRemark[$keyStd.$student->studentID]) ? ($markTotal[$keyStd.$student->studentID] >=100 ?  $numberFormatter->format(1) : $numberFormatter->format($getSubjectPosition[$keyStd.$student->studentID])) : '<i class="ft-x font-medium-3 mr-2"></i>') !!}  
                                                </td>
                                                @endif
                                                <td>
                                                    <!--Signature-->
                                                     <small><small>{!! (($computedBy[$keyStd.$student->studentID]) ? $computedBy[$keyStd.$student->studentID] : '<i class="ft-x font-medium-3 mr-2"></i>') !!}</small></small>  
                                                </td>
                                                
                                            </tr>
                                            @php $keyStd ++; @endphp
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="8"> <span class="text-danger text-center"><b>No Result Found !</b></span> </td>
                                            </tr>
                                        @endforelse
                                        @else
                                            <tr class="text-center">
                                                <td colspan="8"> <span class="text-danger text-center"><b>No Result found !</b></span> </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                               </div>
                            </div>