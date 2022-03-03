@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Extra Curricular :: '. Auth::user()->name)) }} @endsection
@section('extraCurricularPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

          <form class="form" method="post" action="{{ route('postExtra') }}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CREATE EXTRA CURRICULAR/STUDENT ACTIVITIES') }}</h4>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                        
                            <div class="col-md-8 offset-md-2">
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
	                    		<div class="row">
	                    			<div class="form-group col-md-6 mb-2">
			                            <label for="extraCurriculumName {{ $errors->has('extraCurriculumName') ? ' is-invalid' : '' }}">Extra Curriculum Name<span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('extraCurriculumName')}}" autofocus class="form-control" required placeholder="Enter Activity" name="extraCurriculumName">
				                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('extraCurriculumName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('extraCurriculumName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
			                            <label for="extraCurriculumDescription {{ $errors->has('extraCurriculumDescription') ? ' is-invalid' : '' }}">Extra Curriculum Description</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{old('extraCurriculumDescription')}}" class="form-control" placeholder="Enter Description" name="extraCurriculumDescription">
				                            <div class="form-control-position">
				                                <i class="fa fa-briefcase"></i>
                                            </div>
                                            @if ($errors->has('extraCurriculumDescription'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('extraCurriculumDescription') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
								</div><!--//row-->
                                
                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Add') }}</button>
                                    </div>
								</div><!--//row-->
                            </div><!--//col-8-->

                        <div align="center" class="col-md-12">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead>
                                <tr style="background:#d9d9d9">
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('Extra Curriculum') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allExtraCurricular as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->curricular_name ) }}</th>
                                    <th>{{ __($list->curricular_description) }}</th>
                                    <th>{{ __($list->created_at) }}</th>
                                    <th><a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#deleteCurricula{{$list->curricularID}}"><i class="fa fa-trash"></i></a></th>
                                </tr>

                                <!-- Subject Modal -->
                                <div class="modal fade text-left" id="deleteCurricula{{$list->curricularID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Extra Curriculum')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->curricular_name) }} </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeExtra', [$list->curricularID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
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
                            <hr />
                            <div class="row">
                              <div align="right" class="col-xs-12 col-sm-12">
                                  Showing {{($allExtraCurricular->currentpage()-1)*$allExtraCurricular->perpage()+1}}
                                  to {{$allExtraCurricular->currentpage()*$allExtraCurricular->perpage()}}
                                  of  {{$allExtraCurricular->total()}} entries
                                  <br />
                                  <div class="hidden-print">{{ $allExtraCurricular->links() }}</div> 
                              </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
        </form>
    </div><!--end content-wrapper-->
</div><!--end main content-->   

@endsection