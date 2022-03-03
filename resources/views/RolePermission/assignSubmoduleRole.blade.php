@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Assign SubModule To Role:: '. Auth::user()->name)) }} @endsection
@section('AssignSubmoduleToRole') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                            
                            <h4 class="card-title" id="from-actions-multiple">{{ __('ASSIGN USER TO ROLE') }}</h4>
                            <hr />
                            <form class="form" method="post" action="{{route('assignRoleToUser')}}">
                                @csrf
                                <div class="row offset-md-1">
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select User') }} </label>
                                            <select class="form-control" required name="userName" id="userName">
                                                <option value=""> Select User </option>
                                                @forelse($allUser as $userList)
                                                    <option value="{{ $userList->id }}" {{ ($userList->id == old('userName') ? 'slected' : '' )}}>{{ __($userList->userRegistrationId .' - '. $userList->name .' '. $userList->other_name) }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @if ($errors->has('userName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('userName') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select Role') }} </label>
                                            <select class="form-control" required name="roleName" id="roleName">
                                                <option value=""> Select Role </option>
                                                @forelse($allRole as $userRole)
                                                    <option value="{{ $userRole->roleID }}" {{ ($userRole->roleID == old('roleName') ? 'slected' : '' )}}>{{ __($userRole->role_name) }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @if ($errors->has('roleName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('roleName') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div><!--//row-->
                                
                                <div class="card-body">
                                    <div class="px-3">
                                            <div class="form-actions top clearfix">
                                                <div class="buttons-group text-center">
                                                    <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                        <i class="fa fa-check-square-o"></i> {{ __('Assign Now') }}
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </form>	
                            
                        <form class="form" method="post" action="{{route('postSubmoduleAssignment')}}">
                            @csrf
                            <h4 class="card-title" id="from-actions-multiple">{{ __('ASSIGN SUBMODULE TO ROLE') }}</h4>
                            <hr />
                            <div class="row">

                                <div align="center" class="col-md-12">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update</button>
                                </div>

                                <div align="center" class="col-md-12">
                                    <table class="table table-hover table-stripped table-responsive table-condensed"> 
                                    <thead>
                                        <tr style="background:#d9d9d9">
                                            <th class="text-left text-success">SN</th>
                                            <th class="text-left text-success">Role & Permission</th>
                                            @forelse($allRole as $keyRole=>$listRole)
                                            <th>{{ $listRole->role_name }}</th>
                                            @empty
                                            @endforelse
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($allSubModule as $key=>$listModule)
                                            <tr>
                                                <td class="text-left text-success" style="background:#f9f9f9;">{{ 1+ $key}}</td>
                                                <td class="text-left text-success" style="background:#f9f9f9;"><b> {{ $listModule->submodule_name }} </b></td> 
                                                @forelse($allRole as $keyRole=>$listRole)
                                                    <td> 
                                                        @if($listRole->roleID == 1)
                                                            <input type="hidden" value="{{ $listModule->submoduleID.'-'.$listRole->roleID }}" id="permission{{ $listModule->submoduleID.'-'.$listRole->roleID }}"  />
                                                            <input type="hidden" value="{{ $assignSubmoduleID[$key.$keyRole] ? $assignSubmoduleID[$key.$keyRole] : 0 }}" />
                                                            <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                                <input type="checkbox" value="" class="custom-control-input permission" id="{{ $listModule->submoduleID.'-'.$listRole->roleID }}" disabled="disabled" {{ ($getPermission[$key.$keyRole] ? 'checked' : 'checked') }} />
                                                                <label class="custom-control-label" for="{{ $listModule->submoduleID.'-'.$listRole->roleID }}"></label>
                                                            </div>
                                                        @else
                                                            
                                                            <input type="hidden" value="{{ (($getPermission[$key.$keyRole]) ? $listModule->submoduleID.'-'.$listRole->roleID : 0) }}" id="permission{{ $listModule->submoduleID.'-'.$listRole->roleID }}" name="assignSubmoduleRole[]" />
                                                            <input type="hidden" value="{{ $assignSubmoduleID[$key.$keyRole] ? $assignSubmoduleID[$key.$keyRole] : 0 }}" name="assignSubmoduleID[]"  />
                                                            <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                                <input type="checkbox" class="custom-control-input permission" id="{{ $listModule->submoduleID.'-'.$listRole->roleID }}" {{ ($getPermission[$key.$keyRole] ? 'checked' : '') }} />
                                                                <label class="custom-control-label" for="{{ $listModule->submoduleID.'-'.$listRole->roleID }}"></label>
                                                            </div>
                                                        @endif
                                                    </td> 
                                                @empty
                                                @endforelse
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{$totalRole}}" class="text-center text-danger"> No Module to be assigned </td>
                                            </tr>
                                        @endforelse

                                    </thead>
                                    </table> 
                                </div>
                                <div align="center" class="col-md-12">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update</button>
                                </div>
                                <div align="right" class="col-md-12"><hr />
                                    Showing {{($allSubModule->currentpage()-1)*$allSubModule->perpage()+1}}
                                            to {{$allSubModule->currentpage()*$allSubModule->perpage()}}
                                            of  {{$allSubModule->total()}} entries
                                </div>
                                <div class="d-print-none">{{ $allSubModule->links() }}</div>
                            </div><!--//row-->
                            </form>	
                    </div>
                </div>
            </div>
        </div>
        
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //Permission
        $(".permission" ).click(function() { 
            var getID = this.id; 
            if($(this).prop("checked")) { 
                $('#permission' + getID).val(getID);
            }else{ 
                $('#permission' + getID).val(0);
            }
        }); //end function
        
    });//end document
</script>
<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
    @include('PartialView.getSudentListWithClassID')
<!--End get subject-->
@endsection