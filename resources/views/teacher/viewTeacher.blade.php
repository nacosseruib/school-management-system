@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Viewing User' )) }} @endsection
@section('viewAllTeacherPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div align="center">
                                <div align="center" class="col-sm-12 mb-3">
                                    <img height="100" src="{{ asset(Session::get('path') . Session::get('getSchoolProfile')->logo) }}" class="rounded-circle img-responsive" alt=" " />
                                    <div><h5><b> {{ strtoupper(Session::get('getSchoolProfile')->school_full_name) }} </h5></b></div>
                                </div>
                                <h6><b>USER'S/TEACHER'S INFORMATION</b></h6> 
                            </div>

                            <br />

                            <div class="row">
                                <div align="left" class="col-sm-8 mb-3">
                                    <div> {{Session::get('getSchoolProfile')->school_full_name}} </div>
                                    <div> {{Session::get('getSchoolProfile')->slogan}} </div>
                                    <div> {{Session::get('getSchoolProfile')->email}} </div>
                                    <div> {{Session::get('getSchoolProfile')->website}} </div>
                                    <div> {{Session::get('getSchoolProfile')->phone_no}} </div>
                                    <div> {{Session::get('getSchoolProfile')->registration_no }} </div>
                                </div>
                                <div align="center" class="col-sm-4 mb-3">
                                    <img width="120" src="{{ (($userDetails->photo) ? asset($path . $userDetails->photo) : asset($path .'noPicture.png')) }}" class="img-thumbnail img-responsive" alt=" " />
                                </div>
                            </div>

                            <div class="row">
                                <div align="center" class="col-sm-5">
                                    <div class="alert alert-secondary"> 
                                        <i class="fa fa-user"></i> <b>{{ strtoupper($userDetails->name .' '. $userDetails->other_name ) }}</b>
                                    </div>
                                </div>
                                <div align="center" class="col-sm-3">
                                    <div class="alert alert-secondary"> 
                                    <i class="fa fa-check"></i> <b>{{ $userDetails->userRegistrationId }}</b>
                                    </div>
                                </div>
                                <div align="center" class="col-sm-4">
                                    <div class="alert alert-secondary"> 
                                    <i class="fa fa-book"></i> <b>{{ $userDetails->designation }}</b>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="">
                                <div align="center" class="col-md-12">
                                <table class="table table-hover table-striped table-responsive table-condensed">
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Admitted Date: ')}} </td>
                                        <td> {{ strtoupper($userDetails->admitted_date ) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Gender: ')}} </td>
                                        <td> {{ strtoupper($userDetails->gender) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper("Teacher's Class: ")}} </td>
                                        <td> 
                                            @foreach($userClass as $listClass)
                                                {{ (($listClass->class_name) ? ($listClass->class_name) .',' : '')}}
                                            @endforeach
                                         </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Address: ')}} </td>
                                        <td> {!! strtoupper($userDetails->address) !!} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Guarantor First Name: ')}} </td>
                                        <td> {{ strtoupper($userDetails->guarantor_firstname) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Guarantor Laste Name: ')}} </td>
                                        <td> {{ strtoupper($userDetails->guarantor_lastname ) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Guarantor Mobile: ')}} </td>
                                        <td> {{ strtoupper($userDetails->guarantor_telephone ) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Guarantor Email Address: ')}} </td>
                                        <td> {{ strtoupper($userDetails->guarantor_email ) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Guarantor Occupation: ')}} </td>
                                        <td> {{ strtoupper($userDetails->guarantor_occupation) }} </td>
                                    </tr>
                                    <tr class="text-left">
                                        <td> {{ strtoupper('Guarantor Phisical Address: ')}} </td>
                                        <td> {!! strtoupper($userDetails->guarantor_address) !!} </td>
                                    </tr>
                                    
                                </table>
                                </div>
                            </div><!--//row-->
                            <a href="{{ route('viewAllTeacher') }}" class="btn btn-success d-print-none center">Go Back</a>
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