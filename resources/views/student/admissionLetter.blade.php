@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Print Admission Letter' )) }} @endsection
@section('viewAllStudentPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
                <span class="d-print-none">@include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])</span>
          
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                            <div style="min-height:1450px;"><!--start letter-->
                                <div align="center">
                                    @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"Admission Letter", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                                </div>
                                <br />
                                <div class="row">
                                    <div align="left" class="col-sm-7 mb-3">
                                        @if(Session::get('getSchoolProfile'))
                                        <div> {{Session::get('getSchoolProfile')->school_full_name}} </div>
                                        <!--<div> {{Session::get('getSchoolProfile')->slogan}} </div>-->
                                        <div> {{Session::get('getSchoolProfile')->email}} </div>
                                        <div> {{Session::get('getSchoolProfile')->website}} </div>
                                        <div> {{Session::get('getSchoolProfile')->phone_no}} </div>
                                        <div> {{Session::get('getSchoolProfile')->registration_no }} </div>
                                        @endif
                                    </div>
                                    <div align="right" class="col-sm-5 mb-3">
                                        <img width="120" style="max-height: 4; max-width: 110px;" src="{{ (($student->photo) ? asset($path . $student->photo) : asset($path .'noPicture.png')) }}" class="img-thumbnail img-responsive" alt=" " />
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-12">
                                    <div>To:</div>
                                    <div align="right">Date of Admission: {{ date("dS F, Y", strtotime(($student->admitted_date))) }}</div>
                                    <p><b>{{ strtoupper($student->student_lastname .' '. $student->student_firstname) }}, </b></p>
                                    
                                    <p><b>Subject: Notification of Admission</b></p>
                                    
                                    <p>Dear Parent,</p>
                                    
                                    <div><p>Congratulations!</p></div>
                                    
                                    <div>
                                        <p>
                                            We are pleased to inform you that your child <b>{{ strtoupper($student->student_lastname .' '. $student->student_firstname) }}, </b> has been admitted to class: <b>{{ $student->admitted_class }}</b>  for <b>{{ $student->admitted_session }}</b> session 
                                            with student Registration number: <b>{{ strtoupper($student->student_regID) }}</b>. {{ strtoupper($student->student_gender) == "MALE" ? 'He' : 'She' }} is now officially a student of {{Session::get('getSchoolProfile')->school_full_name}}.
                                            We wish {{ strtoupper($student->student_gender) == "MALE" ? 'him' : 'her' }} all the best for a new chapter ahead!
                                        </p>
                                        </p>
                                            Here attached lists that will help you move ahead with the admission procedure.
                                            The lists have all the necessary details mentioned like fees, required documents and deadline to pay fees.
                                        </p>
                                        <p>
                                            Welcome to {{Session::get('getSchoolProfile')->school_full_name}}.
                                        </p>
                                        <p>
                                            Best Regards, <br />
                                            Thanks.
                                        </p>
                                    </div>
                                        
                                    <div class="row">
                                        <div align="center" class="col-sm-4"> 
                                            &nbsp;&nbsp;&nbsp; OLALEKE ABIODUN <br />
                                            ...........................................
                                            <div align="center">
                                                <img style="width: 70%; height:60px;" src="{{ (Session::get('getSchoolProfile') ? asset(Session::get('path') . Session::get('getSchoolProfile')->signature) : '') }}" class="img-responsive" alt=" " />
                                            </div>
                                            &nbsp;&nbsp;&nbsp; Principal's Signature <br /> 
                                            <small><i>Date: {{ date("d/m/Y", strtotime(($student->admitted_date))) }}</i></small>
                                        </div>
                                        <div align="center" class="offset-sm-3 col-sm-4">
                                            
                                            <br /><br /><br /><br />
                                            ...........................................<br />
                                            &nbsp;&nbsp;&nbsp; Admission officer <br />
                                            <small><i>Date: </i></small>
                                        </div>
                                    </div>
                                    
                                    
                                    </div>
                                </div>
                            </div>
                            
                            @if(Session::get('getSchoolProfile') and ((Session::get('getSchoolProfile')->show_fee_breakdown == 1) or (Session::get('getSchoolProfile')->show_fee_breakdown == 3)) )
                                <!--Fees Enquiry-->
                                @include('PartialView.feesEnquiry')
                            @endif
                            <div align="center" class="d-print-none">
                                <a href="{{ route('viewAllStudent') }}" class="btn btn-success d-print-none text-center">Go Back To Student List</a>
                            </div>
                        </div>
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            
        
    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        
    });//end document
</script>
@endsection