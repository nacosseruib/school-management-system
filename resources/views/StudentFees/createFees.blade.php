@extends('layouts.authLayout') 
@section('pageHeaderTitle', strToUpper(__('Fees Setup')))
@section('createFeesPageActive', 'active')
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          <form class="form" method="post" action="{{ route('storeFees') }}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-print-none">
                            <h4 class="card-title" id="from-actions-multiple"><b>{{ __('CREATE FEES SETUP') }}</b></h4>
                            <span class="pull-right">All fields with <b class="text-danger">*</b> are important</span>
                            <hr />
                        
                            <div class="col-md-8 offset-md-2">
                                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
	                    		<div class="row">
	                    			<div class="form-group col-md-8 mb-2">
			                            <label for="feeName {{ $errors->has('feeName') ? ' is-invalid' : '' }}">Fee Name <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($feeSetup)) ? $feeSetup->fees_name : old('feeName')}}" autofocus class="form-control" required placeholder="Fee Name" name="feeName">
				                            <div class="form-control-position">
				                                <i class="fa fa-money"></i>
                                            </div>
                                            @if ($errors->has('feeName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('feeName') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="feeAmount {{ $errors->has('feeAmount') ? ' is-invalid' : '' }}">Fee Amount <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($feeSetup)) ? $feeSetup->amount : old('feeAmount')}}" class="form-control" required placeholder="Fee Amount" name="feeAmount">
				                            <div class="form-control-position">
				                                <i class="fa fa-money"></i>
                                            </div>
                                            @if ($errors->has('feeAmount'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('feeAmount') }}</strong>
                                                </span>
                                            @endif
			                            </div>
                                    </div>
                                </div><!--//row-->

                                <div class="row">
                                    <div class="form-group col-md-8 mb-2">
			                            <label for="feeType {{ $errors->has('feeDurationType') ? ' is-invalid' : '' }}">Fee Mode (Duration) <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="feeDurationType">
                                                <option value="">Select Fee Duration Type</option>
                                                <option value="1" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 1) ? 'selected' : ''}}>To be paid 1st Term only</option>
                                                <option value="2" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 2) ? 'selected' : ''}}>To be paid 2nd Term only</option>
                                                <option value="3" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 3) ? 'selected' : ''}}>To be paid 3rd Term only</option>
                                                <option value="4" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 4) ? 'selected' : ''}}>To be paid per session only</option>
                                                <option value="5" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 5) ? 'selected' : ''}}>To be paid Monthly only</option>
                                                <option value="6" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 6) ? 'selected' : ''}}>To be paid Weekly only</option>
                                                <option value="7" {{ (isset($feeSetup) and $feeSetup->fees_occurent_type == 7) ? 'selected' : ''}}>To be paid Daily only</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-gear"></i>
                                            </div>
                                            @if ($errors->has('feeDurationType'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('feeDurationType') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
                                    <div class="form-group col-md-4 mb-2">
			                            <label for="feeType {{ $errors->has('feeStatus') ? ' is-invalid' : '' }}">Status <span class="text-danger">*</span></label>
			                            <div class="position-relative has-icon-left">
                                            <select class="form-control" required name="feeStatus">
                                                <option value="">Select Status</option>
                                                <option value="1" {{ (isset($feeSetup) and $feeSetup->status == 1) ? 'selected' : ''}}>Enable</option>
                                                <option value="0" {{ (isset($feeSetup) and $feeSetup->status == 0) ? 'selected' : ''}}>Disable</option>
                                            </select>
				                            <div class="form-control-position">
				                                <i class="fa fa-check"></i>
                                            </div>
                                            @if ($errors->has('feeStatus'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('feeStatus') }}</strong>
                                                </span>
                                            @endif
			                            </div>
			                        </div>
								</div><!--//row-->
                                
                                <div class="row">
	                    			<div class="form-group col-md-12 mb-2">
			                            <label for="timesheetinput2 {{ $errors->has('description') ? ' is-invalid' : '' }}">Fee Description</label>
			                            <div class="position-relative has-icon-left">
			                            	<input type="text" value="{{ (isset($feeSetup)) ? $feeSetup->fees_description : old('description')}}" class="form-control" placeholder="Short Description (Optional)" name="description">
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
	                    			<div class="form-group col-md-12 mb-2">
                                        <input type="hidden" name="feesSetupID" value="{{ ((isset($feeSetup)) ? $feeSetup->feessetupID : '') }}" />
                                        @if(isset($feeSetup))
                                            <a href="{{ route('cancelEditFees') }}"  class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Cancel Edit
                                            </a>
                                        @endif
			                            <button class="btn btn-success pull-right"><i class="fa fa-save"></i> {{ __('Add/Update') }}</button>
                                    </div>
								</div><!--//row-->
                            </div><!--//col-8-->
                        </div>
                        
                        <h4 class="card-title text-success text-center mt-2" id="from-actions-multiple"><b>{{ __('LIST OF FEES') }}</b></h4>
                      

                        <div align="center" class="col-md-12">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead>
                                <tr style="background:#d9d9d9">
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('Fees Name') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                   <!--<th>{{ __('Created') }}</th>-->
                                    <th>{{ __('Update') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th colspan="2" class="d-print-none"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allFeesSetup as $key=>$list)
                                <tr class="text-left">
                                    <td>{{($allFeesSetup->currentpage()-1) * $allFeesSetup->perpage() + (1 + $key ++)}}</td>
                                    <td class="text-info"><b>{{ __($list->fees_name) }}</b></td>
                                    <td bgcolor="#f0f0f0" class="text-success"><b>&#x20a6;{{ number_format($list->amount, 2) }}</b></td>
                                    <td>{{ ($list->fees_occurent_type ==1 ? '1st Term Only' :'') . ($list->fees_occurent_type ==2 ? '2nd Term Only' :'')  . ($list->fees_occurent_type ==3 ? '3rd Term Only' :'') . ($list->fees_occurent_type ==4 ? 'Per Session Only' :'') }}</td>
                                    <!--<td>{{ __($list->created_at) }}</td>--> 
                                    <td>{{ __($list->updated_at) }}</td>
                                    <td>{{ __($list->fees_description) }}</td>
                                    <td>{!! __($list->status ? '<span class="text-success">Active</span>' : '<span class="text-danger">Disabled</span>') !!}</td>
                                    <td class="d-print-none"><a href="{{ route('editFee', ['feesSetupID'=>$list->feessetupID])}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a></td>
                                    <td class="d-print-none"><a href="javascript:;" class="btn btn-warning btn-sm" data-toggle="modal" data-backdrop="false" data-target="#deleteFee{{$list->feessetupID}}"><i class="fa fa-trash"></i></a></td>
                                </tr>

                                <!-- Delete Fees Setup Modal -->
                                <div class="modal fade text-left" id="deleteFee{{$list->feessetupID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                                <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Fees')}}  </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->fees_name) }} </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                                <p>
                                                    <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeFee', [$list->feessetupID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Modal-->
                                @empty
                                    <tr><td colspan="10" class="text-danger">{{ __('No record found!') }}</td></tr>
                                @endforelse
                                <div align="right" class="col-md-12"><hr />
                                     Showing {{($allFeesSetup->currentpage()-1)*$allFeesSetup->perpage()+1}}
                                        to {{$allFeesSetup->currentpage()*$allFeesSetup->perpage()}}
                                        of  {{$allFeesSetup->total()}} entries
                                </div>
                                <div class="d-print-none">{{ $allFeesSetup->links() }}</div>
                                
                                
                                
                                <tr><td colspan="10" class="text-info text-center">Daily, Weekly, Monthly Fees</td></tr>
                                @forelse($activeDailyFees as $key=>$list)
                                <tr class="text-left">
                                    <td>{{ (1 + $key ++) }}</td>
                                    <td class="text-info"><b>{{ __($list->fees_name) }}</b></td>
                                    <td bgcolor="#f0f0f0" class="text-success"><b>&#x20a6;{{ number_format($list->amount, 2) }}</b></td>
                                    <td>{{ ($list->fees_occurent_type ==5 ? 'Monthly Fee Only' :'') . ($list->fees_occurent_type ==6 ? 'Weekly Fee Only' :'')  . ($list->fees_occurent_type ==7 ? 'Daily Fee Only' :'') }}</td>
                                    <!--<td>{{ __($list->created_at) }}</td>--> 
                                    <td>{{ __($list->updated_at) }}</td>
                                    <td>{{ __($list->fees_description) }}</td>
                                    <td>{!! __($list->status ? '<span class="text-success">Active</span>' : '<span class="text-danger">Disabled</span>') !!}</td>
                                    <td class="d-print-none"><a href="{{ route('editFee', ['feesSetupID'=>$list->feessetupID])}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a></td>
                                    <td class="d-print-none"><a href="javascript:;" class="btn btn-warning btn-sm" data-toggle="modal" data-backdrop="false" data-target="#deleteFee{{$list->feessetupID}}"><i class="fa fa-trash"></i></a></td>
                                </tr>

                                <!-- Delete Fees Setup Modal -->
                                <div class="modal fade text-left" id="deleteFee{{$list->feessetupID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                                <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Fees')}}  </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete '.$list->fees_name) }} </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                                <p>
                                                    <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeFee', [$list->feessetupID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end Modal-->
                                @empty
                                    <tr><td colspan="10" class="text-danger">{{ __('No record found!') }}</td></tr>
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