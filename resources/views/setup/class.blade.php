@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('LIST OF ALL CLASSES ::'. Auth::user()->name)) }} @endsection
@section('classPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

          <form class="form" method="post" action="{{ route('createClass') }}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title d-print-none" id="from-actions-multiple">{{ __('CREATE CLASS') }}</h4>
                            <span class="pull-right d-print-none">All fields with <b class="text-danger">*</b> are important</span>
                            <hr class="d-print-none" />
                        
                            <div class="col-md-10 offset-md-1 d-print-none">
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
	                    		<div class="row">
	                    			<div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('classCode') ? ' is-invalid' : '' }}">Class Code <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($class)) ? $class->class_code : old('classCode')}}" autofocus class="form-control" required placeholder="Class Code" name="classCode">
				                            <div class="form-control-position">
				                                <i class="fa fa-book"></i>
                                            </div>
                                            @if ($errors->has('classCode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('classCode') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('className') ? ' is-invalid' : '' }}">Class Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($class)) ? $class->class_name : old('className')}}" class="form-control" required placeholder="Class Name" name="className">
				                            <div class="form-control-position">
				                                <i class="fa fa-book"></i>
                                            </div>
                                            @if ($errors->has('className'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('className') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
								</div><!--//row-->
                                
                                <div class="row">
	                    			<div class="form-group col-md-8 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('description') ? ' is-invalid' : '' }}">Class Description</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($class)) ? $class->description : old('description')}}" class="form-control" placeholder="Description (Optional)" name="description">
				                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="classStatus {{ $errors->has('classStatus') ? ' is-invalid' : '' }}">Class Status <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="classStatus">
                                                <option value="">Select Status</option>
                                                <option value="1" {{ (isset($class) and $class->active == 1) ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{ (isset($class) and $class->active == 0) ? 'selected' : ''}}>Disable</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-check"></i>
                                            </div>
                                            @if ($errors->has('classStatus'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('classStatus') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                </div><!--//row-->

                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
                                        <input type="hidden" name="classID" value="{{ ((isset($class)) ? $class->classID : '') }}" />
                                        @if(isset($class))
                                            <a href="{{ route('cancelEditClass') }}"  class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Cancel Edit
                                            </a>
                                        @endif
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Add/Update') }}</button>
                                    </div>
								</div><!--//row-->
                            </div><!--//col-8-->

                        <div align="center" class="col-md-12">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead>
                                <tr style="background:#d9d9d9">
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('CLass Code') }}</th>
                                    <th>{{ __('CLass Name') }}</th>
                                    <th>{{ __('CLass Description') }}</th>
                                    <th>{{ __('Active') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <th colspan="2" class="d-print-none"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allclass as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->class_code) }}</th>
                                    <th class="text-left">{{ __($list->class_name) }}</th>
                                    <th>{{ __($list->description) }}</th> 
                                    <th>{!! __(($list->active ? '<span class="text-success">Active</span>' : '<span class="text-danger">Disabled</span>')) !!}</th> 
                                    <th>{{ __($list->created_at) }}</th>
                                    <th class="d-print-none"><a href="{{ route('editClass', ['classID'=>$list->classID])}}" ><i class="fa fa-edit"></i></a></th>
                                    <th class="d-print-none"><a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#deleteClass{{$list->classID}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Subject Modal -->
                                <div class="modal fade text-left" id="deleteClass{{$list->classID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Class')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->class_name) }} </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeClass', [$list->classID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Modal-->

                                @empty
                                    <tr><td colspan="6" class="text-danger">{{ __('No record found!') }}</td></tr>
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    
                </div>
            </div>
        </div>
        </form>
    </div><!--end content-wrapper-->
</div><!--end main content-->   

@endsection