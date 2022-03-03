@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Viewing Student' )) }} @endsection
@section('viewAllStudentPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div align="center">
                                @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"Student&#8217;S Information", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                            </div>

                            <br />

                            <div class="row">
                                <div align="left" class="col-sm-7 mb-3">
                                    <div> {{Session::get('getSchoolProfile')->school_full_name}} </div>
                                    <!--<div> {{Session::get('getSchoolProfile')->slogan}} </div>-->
                                    <div> {{Session::get('getSchoolProfile')->email}} </div>
                                    <div> {{Session::get('getSchoolProfile')->website}} </div>
                                    <div> {{Session::get('getSchoolProfile')->phone_no}} </div>
                                    <div> {{Session::get('getSchoolProfile')->registration_no }} </div>
                                </div>
                                <div align="right" class="col-sm-5 mb-3">
                                    <img width="120" style="max-height: 4; max-width: 110px;" src="{{ (($student->photo) ? asset($path . $student->photo) : asset($path .'noPicture.png')) }}" class="img-thumbnail img-responsive" alt=" " />
                                </div>
                            </div>

                            <div class="row">
                                <div align="center" class="col-sm-5">
                                    <div class="alert alert-secondary"> 
                                        <i class="fa fa-user"></i> <b>{{ strtoupper($student->student_lastname .' '. $student->student_firstname) }}</b>
                                    </div>
                                </div>
                                <div align="center" class="col-sm-3">
                                    <div class="alert alert-secondary"> 
                                    <i class="fa fa-check"></i> <b>{{ strtoupper($student->student_regID) }}</b>
                                    </div>
                                </div>
                                <div align="center" class="col-sm-4">
                                    <div class="alert alert-secondary"> 
                                    <i class="fa fa-check"></i> <b>{{ strtoupper('Roll No.: '.$student->student_roll) }}</b>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="">
                                <div align="left" class="col-md-12">
                                    @if($student->graduate == 1 or $student->withdraw == 1)
                                    <div class="mb-2 mt-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div align="center" class="alert alert-info text-center"> {{ ($student->graduate == 1 ? 'GRADUATED STUDENT' : '') . ($student->withdraw == 1 ? 'WITHDRAWN STUDENT' : '') }} </div>
                                    </div>
                                    @endif
                                    <div class="row mb-2 mt-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Admitted Date: ')}}</div>
                                        <div class="col-sm-8 text-right"> {{ date('dS F, Y', strtotime($student->admitted_date)) }} </div>
                                    </div>
                                    @if($student->school_type <> null)
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('School Type: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->school_type_name) }}  </div>
                                    </div>
                                    @endif
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Class: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->class_name ) }} </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Gender: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->student_gender) }}  </div>
                                    </div>
                                    
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Date of Birth: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{  date('dS F, Y', strtotime($student->date_of_birth)) }}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Religion: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->religion) }}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Nationality: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->nationality) }}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('State of Origin: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->state) }}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Home Town: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->home_town) }}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Student Extra: ')}} </div>
                                        <div class="col-sm-8 text-right"> 
                                            @foreach($studentExtra as $listExtra)
                                                {{ (($listExtra->curricular_name) ? ($listExtra->curricular_name) .',' : '')}}
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Student Address: ')}} </div>
                                        <div class="col-sm-8 text-right"> {!! strtoupper($student->student_address) !!}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Parent First Name: ')}}  </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->parent_firstname) }} </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Parent Laste Name: ')}}  </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->parent_lastname ) }}  </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Parent Mobile: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->parent_telephone ) }} </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Parent Email Address: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->parent_email ) }} </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Parent Occupation: ')}} </div>
                                        <div class="col-sm-8 text-right"> {{ strtoupper($student->parent_occupation ) }} </div>
                                    </div>
                                    <div class="row mb-2 p-1" style="border-bottom:1px solid #f3f3f3;">
                                        <div class="col-sm-4 text-left"> {{ strtoupper('Parent Phisical Address: ')}} </div>
                                        <div class="col-sm-8 text-right"> {!! strtoupper($student->parent_address ) !!} </div>
                                    </div>
                                </div>
                            </div><!--//row-->
                            <a href="{{ route('viewAllStudent') }}" class="btn btn-success d-print-none center">Go Back</a>
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