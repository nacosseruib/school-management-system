@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Create Route Module :: '. Auth::user()->name)) }} @endsection
@section('modulePageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  id="submitModuleForm" class="form d-print-none" method="post" action="{{route('addModule')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CREATE ROUTE MODULE') }}</h4>
                            <hr />
                            
                            <div class="row offset-md-1">
                                <div class="col-md-5">
                                    <label> Route/Link Name  <span class="text-danger"><b>*</b></span> </label>
                                    <input class="form-control" autofocus required name="routeName" id="routeName" value="{{ ((isset($editModule)) ? $editModule->module_name : old('routeName')) }}">
                                    @if ($errors->has('routeName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('routeName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <label> {{ __('Route URL') }} </label>
                                    <input type="text" value="{{ ((isset($editModule)) ? $editModule->module_url : '#') }}" class="form-control" name="routeUrl" id="routeUrl">
                                    @if ($errors->has('routeUrl'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('routeUrl') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> Route Rank </label>
                                    <select class="form-control" name="routeRank" id="routeRank">
                                        <option value="{{ ((isset($editModule)) ? $editModule->module_rank : '') }}"> {{ ((isset($editModule)) ? ($editModule->module_rank) : 'Select') }} </option>
                                        @for($rank = 1; $rank <= 100; $rank ++)
                                            <option {{ ($rank == old('routeRank')) ? 'Selected' : '' }}>{{$rank}}</option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('routeRank'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('routeRank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> Route Status <span class="text-danger"><b>*</b></span> </label>
                                    <select class="form-control" required name="routStatus" id="routStatus">
                                        <option value="{{ ((isset($editModule)) ? $editModule->module_active : '') }}"> {{ ((isset($editModule)) ? ($editModule->module_active ? 'Enabled' : 'Disabled') : 'Select') }} </option>
                                        <option value="1" {{ (1 == old('routStatus')) ? 'Selected' : '' }}>Enable</option>
                                        <option value="0" {{ (0 and null <> old('routStatus')) ? 'Selected' : '' }}>Disable</option>
                                    </select>
                                    @if ($errors->has('routStatus'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('routStatus') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                            <!--Active Page-->
                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> Module Icon </label>
                                    <input type="text" value="{{ ((isset($editModule)) ? $editModule->module_icon : old('moduleIcon')) }}" class="form-control" name="moduleIcon" placeholder="fa fa-star" id="moduleIcon">
                                    @if ($errors->has('moduleIcon'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('moduleIcon') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5 mt-2">

                                </div>
                                
                            </div><!--//row-->
                        
                        </div>
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div align="center" class="buttons-group">
                                            <input type="hidden" name="editModuleID" value="{{ ((isset($editModule)) ? $editModule->moduleID : '') }}" />
                                            @if(isset($editModule))
                                            <a href="{{ route('cancelEditModule') }}"  class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Cancel Edit
                                            </a>
                                            @endif
                                            <button  id="checkFields" type="button" type="hidden" data-toggle="modal" data-backdrop="false" data-target="#confirmNewModule" class="btn btn-sm btn-success">
                                                <i class="fa fa-check-square-o"></i> {{ __('Continue') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            </form>
            
            <div class="card row">
            <div align="center" class="card-header col-md-12">
                <table class="table table-hover table-stripped table-responsive table-condensed"> 
                    <thead>
                        <tr style="background:#d9d9d9">
                            <th>{{ __('S/N') }}</th>
                            <th>{{ __('Module Name') }}</th>
                            <th>{{ __('URL') }}</th>
                            <th>{{ __('Rank') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Icon') }}</th>
                            <th>{{ __('Last Updated') }}</th>
                            <th class="d-print-none" colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allModule as $key=>$list)
                        <tr>
                            <th>{{ 1+$key ++ }}</th>
                            <th class="text-left">{{ __($list->module_name) }}</th>
                            <th>{{ __($list->module_url) }}</th>
                            <th>{{ __($list->module_rank) }}</th>
                            <th>
                                {!! __(($list->module_active) ? '<span class="text-success"><small>Enabled</small></span>' : '<span class="text-warning"><small>Disabled</small></span>' ) !!}
                            </th>
                            <th><i class="{{ $list->module_icon }}"></i></th>
                            <th>{{ __($list->updated_at) }}</th>
                            <th class="d-print-none">
                                <a href="{{ route('editModule', ['ID'=>$list->moduleID]) }}"><i class="fa fa-edit"></i></a>
                            </th>
                            <th class="d-print-none">
                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#deleteModule{{$list->moduleID}}"><i class="fa fa-trash"></i></a>
                            </th>
                        </tr>

                            <!-- Delete Modal -->
                                <div class="modal fade text-left d-print-none" id="deleteModule{{$list->moduleID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Route Module')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete Module') }} ! </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeModule', [$list->moduleID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end Modal-->

                        @empty
                            <tr><td colspan="8" class="text-danger">{{ __('No record found!') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
                
        <!--Confirm operation  Modal -->
        <div class="modal fade text-left d-print-none" id="confirmNewModule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Update/Add New Module')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5 class="text-primary"><i class="fa fa-arrow-right"></i> {{ __('You are about adding new route module')}} </h5>
                      <p class="text-center text-warning">
                        {{ __('Are you sure you want to continue with this operation ?')}}
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Edit/Cancel</button>
                        <button type="submit" id="submitForm" class="btn btn-outline-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end Modal-->
    </div><!--end content-wrapper-->
</div><!--end main content-->

@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //check Module name
        $("#checkFields").click(function() { 
            if(($("#routeName").val()) == ''){ 
                alert('You have to enter route name !');
                $("#routeName").focus();
                return false;
            }
            //check Module URL
            if($("#routeUrl").val() == ''){
                alert('You have to enter route url !');
                $("#routeUrl").focus();
                return false;
            }
            //check Route Status
            if($("#routStatus").val() == ''){
                alert('Select route status !');
                $("#routStatus").focus();
                return false;
            }
        }); 

        $("#submitForm").click(function() {
            $('#submitModuleForm').submit();
        }); 
    });//end document
</script>
@endsection