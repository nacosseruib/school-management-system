@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strtoupper(__('Import Marks via Excel file ::'. Auth::user()->name)) }} @endsection
@section('importMarkExcelPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                <div class="card">
                   
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('UPLOAD MARK VIA EXCEL FILE') }}</h4>
                            <span class="pull-right d-print-none">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                            <div class="col-md-10 offset-md-1 d-print-none">
                                
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                                
                                
                                <div class="row offset-md-1">
                                   
                                    <div class="form-group col-md-5 mb-2">
                                        <a href="{{ route('downloadMarkExcel', ['type'=>'xlsx']) }}"><button class="btn btn-info">Download Imported Mark (xlsx)</button></a>
                                    </div>
                                    <div class="form-group col-md-5 mb-2">
                                        <a href="{{ route('downloadMarkExcel', ['type'=>'csv']) }}"><button class="btn btn-info">Download Imported Mark (CSV)</button></a>
                                    </div>
                                   
                                </div><!--//row-->
                               <hr />
                            <form class="form d-print-none" method="post" action="{{ route('importMarkExcel') }}" enctype="multipart/form-data">
                            @csrf
                                <div class="row offset-md-0">
                                    <div class="form-group col-md-8 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('importStudentMark') ? ' is-invalid' : '' }}">Attach Mark (Excel File) <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="file" class="form-control" required placeholder="Attach Excel File" name="importStudentMark">
				                            <div class="form-control-position">
				                                <i class="fa fa-users"></i>
                                            </div>
                                            @if ($errors->has('importStudentMark'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('importStudentMark') }}</strong>
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
                           

                    <form id="uploadForm" class="form" method="post" action="{{ route('submitMarkImported') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($tempMarkExcel))
                            @if(count($tempMarkExcel) > 0)
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
                                    <th>{!! __('Student Reg.&nbsp;No') !!}</th>
                                    <th>{{ __('Class Code') }}</th>
                                    <th>{{ __('Subject Code') }}</th>
                                    <th>{{ __('Session') }}</th>
                                    <th>{{ __('Term') }}</th>
                                    <th>{{ __('1st Test') }}</th>
                                    <th>{{ __('2nd Test') }}</th>
                                    <th>{{ __('Exam') }}</th>
                                    <th>{{ __('Computed By') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody style="font-size:12px;">
                            @if(isset($tempMarkExcel))
                                @forelse($tempMarkExcel as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->student_regID ) }}</th>
                                    <th >{{ __($list->class_name) }}</th>
                                    <th>{{ __($list->subject_name) }}</th>
                                    <th>{{ __($list->session_code) }}</th>
                                    <th>{{ __($list->term_name) }}</th>
                                    <th>{{ __($list->test1) }}</th>
                                    <th>{{ __($list->test2) }}</th>
                                    <th>{{ __($list->exam) }}</th>
                                    <th>{{ __($list->computed_by) }}</th>
                                    <th><a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-backdrop="false" data-target="#deleteStudent{{$list->markID}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade text-left" id="deleteStudent{{$list->markID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Mark')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '. $list->student_regID) }} ! </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __("This record will be deleted permanently !")}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('deleteRowMarkImport', [$list->markID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
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
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-save"></i> {{ __('Confirm your marks')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ "You are about to upload student mark" }} ! </div>
                                            <p>
                                               <h5 class="text-center"><i class="fa fa-users"></i> {{ __('Are you sure you want to continue with this operation?')}} </h5>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <button type="button" id="submitUploadForm" class="btn btn-success">{{ __('Upload and Save') }}</button>
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
    
    $("#submitUploadForm" ).click(function() { 
        $("#uploadForm").submit();
    });
});
</script>
@endsection