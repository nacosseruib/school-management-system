@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('List of All Subjects ::'. Auth::user()->name)) }} @endsection
@section('subjectPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

          <form class="form" method="post" action="{{ route('createSubject') }}">
          @csrf
            <div class="row d-print-none">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title d-print-none" id="from-actions-multiple">{{ __('CREATE SUBJECT') }}</h4>
                            <span class="pull-right d-print-none">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                        
                            <div class="col-md-12 ">
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
	                    		<div class="row">
	                    			<div class="form-group col-md-4 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('subjectCode') ? ' is-invalid' : '' }}">Subject Code <span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($subject)) ? $subject->subject_code : old('subjectCode')}}" autofocus class="form-control" required placeholder="Subject Code" name="subjectCode">
				                            <div class="form-control-position">
				                                <i class="fa fa-book"></i>
                                            </div>
                                            @if ($errors->has('subjectCode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subjectCode') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-5 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('subjectName') ? ' is-invalid' : '' }}">Subject Name <span class="text-danger">*</span> </label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($subject)) ? $subject->subject_name : old('subjectName')}}" class="form-control" required placeholder="Subject Name" name="subjectName">
				                            <div class="form-control-position">
				                                <i class="fa fa-book"></i>
                                            </div>
                                            @if ($errors->has('subjectName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subjectName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                    <div class="form-group col-md-3 mb-2">
			                            <label for="subjectStatus {{ $errors->has('subjectStatus') ? ' is-invalid' : '' }}">Subject Status <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="subjectStatus">
                                                <option value="">Select Status</option>
                                                <option value="1" {{ (isset($subject) and $subject->subjectActive == 1) ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{ (isset($subject) and $subject->subjectActive == 0) ? 'selected' : ''}}>Disable</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-check"></i>
                                            </div>
                                            @if ($errors->has('subjectStatus'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('subjectStatus') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
								</div><!--//row-->
                                
                                <div class="row">
	                    			<div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('className') ? ' is-invalid' : '' }}">Class Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" name="className">
                                                <option value="{{ (isset($subject)) ? $subject->classID : ''}}"> {{ (isset($subject)) ? $subject->class_name : 'Select Class'}}</option>
                                                @forelse($allClass as $class)
                                                    <option value="{{ $class->classID }}" {{ (old('className') == $class->classID ) ? 'selected' : ''}}>{{ $class->class_name}}</option>
                                                @empty
                                                @endforelse
                                            </select>
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
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('description') ? ' is-invalid' : '' }}">Subject Description</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($subject)) ? $subject->subject_description : old('description')}}" class="form-control" placeholder="Description (Optional)" name="description">
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
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="maximumTest1 {{ $errors->has('maximumTest1') ? ' is-invalid' : '' }}">Max. Test1 Score <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($subject)) ? $subject->max_ca1 : 20 }}" required class="form-control" placeholder="Maximum Test1 (Required)" name="maximumTest1">
				                            <div class="form-control-position">
				                                <i class="fa fa-file"></i>
                                            </div>
                                            @if ($errors->has('maximumTest1'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('maximumTest1') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="maximumTest2 {{ $errors->has('maximumTest2') ? ' is-invalid' : '' }}">Max. Test2 Score <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($subject)) ? $subject->max_ca2 : 20 }}" required class="form-control" placeholder="Maximum Test2 (Required)" name="maximumTest2">
				                            <div class="form-control-position">
				                                <i class="fa fa-file"></i>
                                            </div>
                                            @if ($errors->has('maximumTest2'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('maximumTest2') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="maximumExam {{ $errors->has('maximumExam') ? ' is-invalid' : '' }}">Max. Exam Score <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($subject)) ? $subject->max_exam : 60 }}" required class="form-control" placeholder="Maximum Exam (Required)" name="maximumExam">
				                            <div class="form-control-position">
				                                <i class="fa fa-file"></i>
                                            </div>
                                            @if ($errors->has('maximumExam'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('maximumExam') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
                                        <input type="hidden" name="subjectID" value="{{ ((isset($subject)) ? $subject->subjectID : '') }}" />
                                        @if(isset($subject))
                                            <a href="{{ route('cancelEditSubject') }}"  class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Cancel Edit
                                            </a>
                                        @endif
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Add/Update') }}</button>
                                    </div>
								</div><!--//row-->
                        </div><!--//col-8-->
                </div>
            </div>
        </div>
        </div>
    </form>

                <div class="card row">
                    <div align="center" class="card-header col-md-12">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead>
                                <tr style="background:#d9d9d9; font-size:13px">
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('Subject Code') }}</th>
                                    <th>{{ __('Subject Name') }}</th>
                                    <th>{{ __('CLass Name') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Max. Test1') }}</th>
                                    <th>{{ __('Max. Test2') }}</th>
                                    <th>{{ __('Max. Exam') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <th colspan="2" class="d-print-none"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allSubject as $key=>$list)
                                <tr style="font-size:13px">
                                    <th>{{ ($allSubject->currentpage()-1) * $allSubject->perpage() + (1+$key ++) }}</th>
                                    <th>{{ __($list->subject_code) }}</th>
                                    <th>{{ __($list->subject_name) }}</th>
                                    <th>{{ __($list->class_name) }}</th>
                                    <th>{{ __($list->subject_description) }}</th>
                                    <th>{{ __($list->max_ca1) }}</th>
                                    <th>{{ __($list->max_ca2) }}</th>
                                    <th>{{ __($list->max_exam) }}</th>
                                    <th>{!! (($list->subjectActive) ? '<span class="text-success">Active</span>' : '<span class="text-danger">Disabled</span>') !!}</th> 
                                    <th>{{ __($list->subjectDate) }}</th>
                                    <th class="d-print-none"><a href="{{ route('editSubject', ['subjectID'=>$list->subjectID])}}" ><i class="fa fa-edit"></i></a></th>
                                    <th class="d-print-none"><a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#deleteSubject{{$list->subjectID}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Subject Modal -->
                                <div class="modal fade text-left" id="deleteSubject{{$list->subjectID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Subject')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->subject_name) }} </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeSubject', [$list->subjectID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
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
                            <div class="row">
                              <div align="right" class="col-xs-12 col-sm-12">
                                  Showing {{($allSubject->currentpage()-1)*$allSubject->perpage()+1}}
                                  to {{$allSubject->currentpage()*$allSubject->perpage()}}
                                  of  {{$allSubject->total()}} entries
                                  <br />
                                  <div class="hidden-print">{{ $allSubject->links() }}</div> 
                              </div>
                            </div>
                        </div>
                </div>

    </div><!--end content-wrapper-->
</div><!--end main content-->   

@endsection