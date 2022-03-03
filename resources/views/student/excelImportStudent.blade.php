@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strtoupper(__('Upload Student via Excel File ::'. Auth::user()->name)) }} @endsection
@section('studentExcelPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                   
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('UPLOAD STUDENT VIA EXCEL FILE') }}</h4>
                            <span class="pull-right d-print-none">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                            <div class="col-md-10 offset-md-1 d-print-none">
                                
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                                
                                
                                <div class="row">
                                    <!--<div class="form-group col-md-3 mb-2">
                                        <a href="{{ route('downloadExcel', ['type'=>'xls']) }}"><button class="btn btn-info">Download Excel xls</button></a>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <a href="{{ route('downloadExcel', ['type'=>'xlsx']) }}"><button class="btn btn-info">Download Imported Student (xlsx)</button></a>
                                    </div>-->
                                    <div class="form-group col-md-5 mb-2">
                                        <a href="{{ route('downloadExcel', ['type'=>'csv']) }}"><button class="btn btn-info">Download Imported Student (CSV)</button></a>
                                    </div>
                                    <div class="form-group col-md-5 mb-2">
                                        <a href="{{ route('downloadNewExcel', ['type'=>'xlsx']) }}"><button class="btn btn-info">Download Empty Excel (xlsx)</button></a>
                                    </div>
                                </div><!--//row-->
                               <hr />
                            <form class="form d-print-none" method="post" action="{{ route('importExcel') }}" enctype="multipart/form-data">
                            @csrf
                                <div class="row offset-md-0">
                                    <div class="form-group col-md-8 mb-2">
			                            <label for="excelStudentFile {{ $errors->has('excelStudentFile') ? ' is-invalid' : '' }}">Attach Student (Excel File) <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="file" class="form-control" required placeholder="Attach Excel File" name="excelStudentFile">
				                            <div class="form-control-position">
				                                <i class="fa fa-users"></i>
                                            </div>
                                            @if ($errors->has('excelStudentFile'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('excelStudentFile') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
								
	                    			<div align="center" class="form-group col-md-3 mb-2 mt-4">
                                        <label>&nbsp;</label>
			                            <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> {{ __('Start Upload') }}</button>
                                    </div>
                                </div><!--//row-->
                            </form>
                            <hr />
                        </div><!--//col-8-->
                           

                        <form id="registerForm" class="form" method="post" action="{{ route('registerStudentImport') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($tempStudentExcel))
                            @if(count($tempStudentExcel) > 0)
                                <div class="row offset-md-0 d-print-none">
                                    <div align="center" class="form-group col-md-12 mb-2 mt-2">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-backdrop="false" data-target="#registerStudentByBatch"><i class="fa fa-save"></i> {{ __('Continue with upload') }}</button>
                                    </div>
                                </div><!--//row-->
                            @endif
                        @endif

                        <div align="center" class="col-md-12 offset-md-0">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead style="background:#f9f9f9; font-size:12px;">
                                <tr>
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('Admitted Date') }}</th>
                                    <th>{!! __('Student Reg.&nbsp;No') !!}</th>
                                    <th>{{ __('Roll') }}</th>
                                    <th>{{ __('Class Code') }}</th>
                                    <th>{{ __('Surname') }}</th>
                                    <th>{{ __('Other Names') }}</th>
                                    <th>{{ __('Gender') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Parent Surname') }}</th>
                                    <th>{{ __('Parent Other Names') }}</th>
                                    <th>{{ __('Parent Address') }}</th>
                                    <th>{{ __('Mobile') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Occupation') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody style="font-size:12px;">
                            @if(isset($tempStudentExcel))
                                @forelse($tempStudentExcel as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->admitted_date ) }}</th>
                                    <th >{{ __($list->student_regID) }}</th>
                                    <th>{{ __($list->student_roll) }}</th>
                                    <th>{{ __($list->class_name) }}</th>
                                    <th>{{ __($list->student_lastname) }}</th>
                                    <th>{{ __($list->student_firstname) }}</th>
                                    <th>{{ __($list->student_gender) }}</th>
                                    <th>{{ __($list->student_address) }}</th>
                                    <th>{{ __($list->parent_firstname ) }}</th>
                                    <th>{{ __($list->parent_lastname) }}</th>
                                    <th>{{ __($list->parent_address) }}</th>
                                    <th>{{ __($list->parent_telephone ) }}</th>
                                    <th>{{ __($list->parent_email) }}</th>
                                    <th>{{ __($list->parent_occupation ) }}</th>
                                    <th><a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="false" data-target="#deleteStudent{{$list->studentID}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade text-center" id="deleteStudent{{$list->studentID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Student')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->student_lastname.' '. $list->student_firstname .' - '. $list->student_regID) }} ! </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __("This record will be moved to student's recycle bin !")}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('deleteRowImport', [$list->studentID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Modal-->
                                @empty
                                    <tr><td colspan="16" class="text-danger"><b>{{ __('No record found!') }}</b></td></tr>
                                @endforelse
                            @endif
                            </tbody>
                            </table>
                        </div>
                        <!-- Confirm Registration Modal -->
                            <div class="modal fade text-left" id="registerStudentByBatch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-save"></i> {{ __('Confirm Registration')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ "You are about to register all the students imported on this page" }} ! </div>
                                            <p>
                                               <h5 class="text-center"><i class="fa fa-users"></i> {{ __('Are you sure you want to continue with this registration?')}} </h5>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <button type="button" id="submitRegisterForm" class="btn btn-success">{{ __('Register Now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        <!--end Modal-->
                    </form>
                </div>
            </div>
        </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    
    $("#submitRegisterForm" ).click(function() { 
        $("#registerForm").submit();
    });
});
</script>
@endsection