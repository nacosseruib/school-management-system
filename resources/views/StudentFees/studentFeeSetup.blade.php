@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper('Student Fees Setup') }} @endsection
@section('studentFeeSetupHeaderTitle') active @endsection
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
                                <h4 class="card-title" id="from-actions-multiple">{{ __('STUDENT FEES SETUP') }}</h4>
                                <hr />
                            </span>
                            <div>
                                <div align="center" class="col-md-12 mb-3">
                                    @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"STUDENT FEES SETUP", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                                </div>
                            @if(isset($studentDetails) and $studentDetails)
                            <form class="form" id="addMoreFeeForm" method="post" action="{{route('AddMoreFeeStudent')}}">
                            @csrf
                            <div class="col-md-12 d-print-none pt-2" style="background:#f3f3f3">
                                <div class="row mb-2 d-print-none">
                                    <div class="col-md-3">
                                        <div class="text-success text-center"><b> Add Additional Fees: </b></div>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" required name="feeName" Id="feeName">
                                            <option value=""> Select Fee </option>
                                            @forelse($allFeesSetup as $key=>$fee)
                                                <option value="{{$fee->feessetupID}}"> {{$fee->fees_name .' - #'. $fee->amount . ' ['. $fee->term_name .' only]'}} </option>
                                            @empty
                                            @endforelse
                                        </select>
                                        <input type="hidden" name="studentName" value="{{($studentDetails ? $studentDetails->studentID : 0)}}" />
                                        <input type="hidden" name="className" value="{{($studentDetails ? $studentDetails->classID : 0)}}" />
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <button type="button" data-toggle="modal" data-backdrop="false" data-target="#addMoreFee" class="btn btn-raised btn-primary btn-sm mr-1">
                                            <i class="fa fa-save"></i> Add More Fee
                                        </button>
                                    </div> 
                                </div><!--//row-->
                                <!-- Confirming Add Fees  Modal -->
                                <div class="modal fade text-left" id="addMoreFee" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info white">
                                                <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-save"></i> {{ __('Add More Fee')}}  </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Adding More Fee for this student') }} </div>
                                                <h5><i class="fa fa-info"></i> {{ __('Are you sure you want to add the selected fee for this student ?')}} </h5>
                                                <p>
                                                    <div class="text-success text-center"> {{ __('The selected fee will be added for this student and it can be romved any time.')}} </div>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                <button type="submit" class="btn btn-outline-success submitMoreFee">{{ __('Add Now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Confirming Adding Fee Modal-->
                            </div>
                            </form>
                            @endif

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
                                    <thead>
                                        <tr style="background:#d9d9d9">
                                            <th>{{ ('S/N') }}</th>
                                            <th>{!! ("Fee's&nbsp;Name") !!}</th>
                                            <th>{{'Fee Amount'}}</th>
                                            <th>{{'Amount To Be Paid'}}</th>
                                            <th>{!! ("Fee's&nbsp;Duration") !!}</th>
                                            <th colspan="2" class="d-print-none">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--CORE FEES -->
                                        @if(isset($getAllAssignedCoreFees) and isset($studentDetails) and $studentDetails)
                                        @php $serialNo = 1; $subTotalAmountCoreFee = 0; $originalTotalAmountCoreFee =0; @endphp
                                        <tr>
                                            <td colspan="7" class="text-center text-info">CLASS CORE FEES</td>
                                        </tr>
                                        @forelse($getAllAssignedCoreFees as $key=>$listCoreFee)
                                        <tr>
                                            <td>{{($serialNo ++)}}</td>
                                            <td bgcolor="#f9f9f9" class="text-success text-left"><b>{{ $listCoreFee->fees_name}}</b></td>
                                            <th class="text-left"><b>{{number_format($listCoreFee->amount, 2)}}</b></th>
                                            <th bgcolor="#f9f9f9" class="text-center"><b>{{number_format($newCoreStudentFeeAmount[$key . $listCoreFee->feessetupID], 2)}} &nbsp;<span class="text-success"> x 3</span></b></th>
                                            <td class="text-center"> 
                                                {{ ($listCoreFee->fees_occurent_type == 1 ? '1st Term Only' : '') . ($listCoreFee->fees_occurent_type == 2 ? '2nd Term Only' : '')  . ($listCoreFee->fees_occurent_type ==3 ? '3rd Term Only' : '') . ($listCoreFee->fees_occurent_type == 4 ? 'Per Session Only' : '') }} 
                                            </td>
                                            <td colspan="2" class="d-print-none">
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#editCoreFee{{$listCoreFee->feessetupID}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        @php 
                                            $subTotalAmountCoreFee += $newCoreStudentFeeAmount[$key . $listCoreFee->feessetupID]; 
                                            $originalTotalAmountCoreFee += $listCoreFee->amount;
                                        @endphp

                                        <!-- Edit Fees Setup Modal -->
                                        <div class="modal fade text-left" id="editCoreFee{{$listCoreFee->feessetupID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info white">
                                                    <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-edit"></i> {{ __('Edit Fee')}}  </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center text-success"> <b> {{ __('You are about to edit '. $listCoreFee->fees_name .($studentDetails ? ' for '. $studentDetails->student_lastname .' '. $studentDetails->student_firstname .' only !' : '')) }} </b> </div>
                                                        <h5><i class="fa fa-info"></i> The updated fee amount will be used for this student only whenever this fee is assigned to his/her class. </h5>
                                                       <hr />
                                                        <div class="row"><!--//row-->
                                                            <div class="form-group col-md-6 mb-2">
                                                                <label for="newFeeAmount {{ $errors->has('newFeeAmount') ? ' is-invalid' : '' }}">Fee Amount <span class="text-danger">*</span></label>
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" class="form-control" value="{{$newCoreStudentFeeAmount[$key . $listCoreFee->feessetupID]}}" id="newFeeAmount{{$listCoreFee->feessetupID}}" placeholder="Enter New Fee Amount" />
                                                                    @if ($errors->has('newFeeAmount'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('newFeeAmount') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 mb-2">
                                                                <label for="newFeeAmount{{ $errors->has('newFeeAmount') ? ' is-invalid' : '' }}">Fee Name <span class="text-danger">*</span></label>
                                                                <div class="position-relative has-icon-left">
                                                                    <div class="form-control"> {{$listCoreFee->fees_name}} </div>
                                                                </div>
                                                            </div>
                                                        </div><!--//row-->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="button" id="{{$listCoreFee->feessetupID}}" class="btn btn-outline-success submitEditFormBtn">{{ __('Update Now') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Edit Fees Modal-->
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger"> 
                                                    <span class="d-print-none">No Core Fee assigned to this student's class ! </span>
                                                    <br />
                                                    <a href="{{route('classFeeSetup')}}" class="btn btn-success mt-2  d-print-none"><i class="fa fa-save"></i> Click here to assign Fees to this class </a>
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr bgcolor="#d0d0d0">
                                            <td colspan="2" class="text-center text-info"><b>SUB-TOTAL:</b></td>
                                            <td class="text-center text-info"><b>{{number_format(($originalTotalAmountCoreFee), 2)}}</b></td>
                                            <td class="text-center text-info"><b>{{number_format(($subTotalAmountCoreFee), 2)}}</b></td>
                                            <td colspan="3"></td>
                                        </tr>
                                        @endif


                                        <!--PERIODIC FEES -->
                                        @if(isset($getAllAssignedFees) and isset($studentDetails) and $studentDetails)
                                        @php $subTotalAmountFee = 0; $originalTotalAmountFee = 0; @endphp
                                        <tr>
                                            <td colspan="7" class="text-center text-info">CLASS PERIODIC FEES</td>
                                        </tr>
                                        @forelse($getAllAssignedFees as $keyFee=>$listFee)
                                        <tr>
                                            <td>{{$serialNo ++}}</td>
                                            <td bgcolor="#f9f9f9" class="text-success text-left"><b>{{ $listFee->fees_name}}</b></td>
                                            <th class="text-left"><b>{{number_format($listFee->amount, 2)}}</b></th>
                                            <th bgcolor="#f9f9f9" class="text-center"><b>{{number_format($newStudentFeeAmount[$keyFee . $listFee->feessetupID], 2)}}</b></th>
                                            <td class="text-center"> 
                                                {{ ($listFee->fees_occurent_type ==1 ? '1st Term Only' : '') . ($listFee->fees_occurent_type ==2 ? '2nd Term Only' : '')  . ($listFee->fees_occurent_type ==3 ? '3rd Term Only' : '') . ($listFee->fees_occurent_type ==4 ? 'Per Session Only' : '') }} 
                                            </td>
                                            <td colspan="2" class="d-print-none">
                                            <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#editFee{{$listFee->feessetupID}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        @php
                                            $subTotalAmountFee += $newStudentFeeAmount[$keyFee . $listFee->feessetupID];
                                            $originalTotalAmountFee += $listFee->amount;
                                        @endphp
                                         <!-- Edit Fees Setup Modal -->
                                         <div class="modal fade text-left" id="editFee{{$listFee->feessetupID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info white">
                                                    <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-edit"></i> {{ __('Edit Fee')}}  </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center text-success"> <b> {{ __('You are about to edit '. $listFee->fees_name .' for '. $studentDetails->student_lastname .' '. $studentDetails->student_firstname .' only !') }} </b> </div>
                                                        <h5><i class="fa fa-info"></i> The updated fee amount will be used for this student only whenever this fee is assigned to his/her class. </h5>
                                                       <hr />
                                                        <div class="row"><!--//row-->
                                                            <div class="form-group col-md-6 mb-2">
                                                                <label for="newFeeAmount {{ $errors->has('newFeeAmount') ? ' is-invalid' : '' }}">Fee Amount <span class="text-danger">*</span></label>
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" class="form-control" value="{{$newStudentFeeAmount[$keyFee . $listFee->feessetupID]}}" id="newFeeAmount{{$listFee->feessetupID}}" placeholder="Enter New Fee Amount" />
                                                                    @if ($errors->has('newFeeAmount'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('newFeeAmount') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 mb-2">
                                                                <label for="newFeeAmount{{ $errors->has('newFeeAmount') ? ' is-invalid' : '' }}">Fee Name <span class="text-danger">*</span></label>
                                                                <div class="position-relative has-icon-left">
                                                                    <div class="form-control"> {{$listFee->fees_name}} </div>
                                                                </div>
                                                            </div>
                                                        </div><!--//row-->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="button" id="{{$listFee->feessetupID}}" class="btn btn-outline-success submitEditFormBtn">{{ __('Update Now') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Edit Fees Modal-->
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger"> 
                                                <span class="d-print-none"> No Periodic Fee assigned to this student's class ! </span>
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr bgcolor="#d0d0d0"> 
                                            <td colspan="2" class="text-center text-info"><b>SUB-TOTAL:</b></td>
                                            <td class="text-center text-info"><b>{{number_format(($originalTotalAmountFee), 2)}}</b></td>
                                            <td class="text-center text-info"><b>{{number_format(($subTotalAmountFee), 2)}}</b></td>
                                            <td colspan="3"></td>
                                        </tr>

                                         <!--STUDENT ADDITIONAL FEES-->
                                        @if(isset($getAllAdditionStudentFee))
                                        @php $subTotalAmountAdditioanlFee = 0; $originalTotalAmountAdditionalFee = 0; @endphp
                                        <tr>
                                            <td colspan="7" class="text-center text-info">STUDENT ADDITIONAL FEES</td>
                                        </tr>
                                        @forelse($getAllAdditionStudentFee as $keyPFee=>$additionalFee)
                                        <tr>
                                            <td>{{$serialNo ++}}</td>
                                            <td bgcolor="#f9f9f9" class="text-success text-left"><b>{{ $additionalFee->fees_name}}</b></td>
                                            <th class="text-left"><b>{{number_format($additionalFee->amount, 2)}}</b></th>
                                            <th bgcolor="#f9f9f9" class="text-center"><b>{{number_format($newStudentAdditionalFeeAmount[$keyPFee . $additionalFee->feessetupID], 2)}}</b></th>
                                            <td class="text-center"> 
                                                {{ ($additionalFee->fees_occurent_type ==1 ? '1st Term Only' : '') . ($additionalFee->fees_occurent_type ==2 ? '2nd Term Only' : '')  . ($additionalFee->fees_occurent_type ==3 ? '3rd Term Only' : '') . ($additionalFee->fees_occurent_type ==4 ? 'Per Session Only' : '') }} 
                                            </td>
                                            <td class="d-print-none">
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#editFee{{$additionalFee->feessetupID}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                            </td>
                                            <td class="d-print-none">
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#removeFee{{$additionalFee->studentfeesetupID}}" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @php 
                                            $subTotalAmountAdditioanlFee += $newStudentAdditionalFeeAmount[$keyPFee . $additionalFee->feessetupID];
                                            $originalTotalAmountAdditionalFee += $additionalFee->amount;
                                        @endphp
                                         <!-- Edit Fees Setup Addition fee Modal -->
                                         <div class="modal fade text-left" id="editFee{{$additionalFee->feessetupID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info white">
                                                    <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-edit"></i> {{ __('Edit Fee')}}  </h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center text-success"> <b> {{ __('You are about to edit '. $additionalFee->fees_name .' for '. $studentDetails->student_lastname .' '. $studentDetails->student_firstname .' only !') }} </b> </div>
                                                        <h5><i class="fa fa-info"></i> The updated fee amount will be used for this student only whenever this fee is assigned to his/her class. </h5>
                                                       <hr />
                                                        <div class="row"><!--//row-->
                                                            <div class="form-group col-md-6 mb-2">
                                                                <label for="newFeeAmount {{ $errors->has('newFeeAmount') ? ' is-invalid' : '' }}">Fee Amount <span class="text-danger">*</span></label>
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" class="form-control" value="{{$newStudentAdditionalFeeAmount[$keyPFee . $additionalFee->feessetupID]}}" id="newFeeAmount{{$additionalFee->feessetupID}}" placeholder="Enter New Fee Amount" />
                                                                    @if ($errors->has('newFeeAmount'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('newFeeAmount') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 mb-2">
                                                                <label for="newFeeAmount{{ $errors->has('newFeeAmount') ? ' is-invalid' : '' }}">Fee Name <span class="text-danger">*</span></label>
                                                                <div class="position-relative has-icon-left">
                                                                    <div class="form-control"> {{$additionalFee->fees_name}} </div>
                                                                </div>
                                                            </div>
                                                        </div><!--//row-->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <button type="button" id="{{$additionalFee->feessetupID}}" class="btn btn-outline-success submitEditFormBtn">{{ __('Update Now') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Edit Fees Modal-->

                                         <!-- Delete Additional Fees Setup Modal -->
                                        <div class="modal fade text-left" id="removeFee{{$additionalFee->studentfeesetupID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger white">
                                                        <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Remove Additional Fee')}}  </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">  {{ __('Remove permanantly: ' . $additionalFee->fees_name) }} </div>
                                                        <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to remove this fee for this student?')}} </h5>
                                                        <p>
                                                            <div class="text-danger text-center"> {{ __('This fee will be removed from this student list of fees !')}} </div>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                                        <a href="{{ route('removeAdditionalFee', [$additionalFee->studentfeesetupID])}}" class="btn btn-outline-danger">{{ __('Remove Now') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end Modal-->
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger"> 
                                                    <span class="d-print-none">No Additional Fee assigned to this Student !</span> 
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr bgcolor="#d0d0d0">
                                            <td colspan="2" class="text-center text-info"><b>SUB-TOTAL:</b></td>
                                            <td class="text-center text-info"><b>{{number_format(($originalTotalAmountAdditionalFee), 2)}}</b></td>
                                            <td class="text-center text-info"><b>{{number_format(($subTotalAmountAdditioanlFee), 2)}}</b></td>
                                            <td colspan="3"></td>
                                        </tr>
                                        @endif
                                        
                                        <tr  bgcolor="black">
                                            <td colspan="2" class="text-center text-danger"><b>TOTAL FEES:</b></td>
                                            <td class="text-center text-info"><b><b>{{number_format(($originalTotalAmountCoreFee + $originalTotalAmountFee + $originalTotalAmountAdditionalFee), 2)}}</b></b></td>
                                            <td class="text-center text-danger"><b><b>{{number_format(($subTotalAmountFee + $subTotalAmountCoreFee + $subTotalAmountAdditioanlFee), 2)}}</b></b></td>
                                            <td colspan="3"></td>
                                        </tr>
                                        
                                         <!--ALL PREVIOUS OUTSTANDING BALANCE-->
                                        <tr bgcolor="#d0d0d0">
                                            <td colspan="2" class="text-center text-danger"><b>PREVIOUS TOTAL OUTSTANDING:</b></td>
                                            <td></td>
                                            <td class="text-center text-danger"><b>{{number_format(($getPreviousOutstandingFees), 2)}}</b></td>
                                            <td colspan="3"></td>
                                        </tr>
                                        
                                        <tr bgcolor="black">
                                            <td colspan="2" class="text-info">TOTAL AMOUNT DUE: <b>{{number_format(($totalAmountDueToBePaidPerStudent), 2)}}</b> </td>
                                            <td colspan="2" class="text-success">AMOUNT PAID: <b>{{number_format(($totalAmountPaid), 2)}}</b> </td>
                                            <td colspan="3" class="text-danger">TOTAL OUTSTANDING: <b>{{number_format(($totalBalanceDue), 2)}}</b> </td>
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
<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
@if(Session::get("getStudentTypeToSearch") == 2)
    @include('PartialView.getSudentListWithClassIDForMark')
@else
    @include('PartialView.getSudentListWithClassID')
@endif
<!--End get subject-->
@endsection