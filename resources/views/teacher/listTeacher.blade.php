@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('All Teacher List ::'. Auth::user()->name)) }} @endsection
@section('viewAllTeacherPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __("LIST OF USERS/TEACHERS") }}</h4>
                            <hr />
                            
                            <div class="row">
                                <div class="col-md-12">
                                    
                                <section id="multi-column">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                
                                                <div class="card-body collapse show">
                                                    <div class="card-block card-dashboard">
                                                        <table class="table table-hover table-striped table-responsive table-condensed">
                                                            <thead style="font-size:14px;">
                                                                <tr>
                                                                    <th>S/N</th>
                                                                    <th>Admitted</th>
                                                                    <th>RegNo.</th>
                                                                    <th>Surname</th>
                                                                    <th>Other&nbsp;Names</th>
                                                                    <th>Gender</th>
                                                                    <th>Email</th>
                                                                    <th>Mobile</th>
                                                                    <th class="hidden-print">Status</th>
                                                                    <th class="hidden-print" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:13px;">
                                                                @forelse($allUserList as $key => $listUser)
                                                                <tr>
                                                                    <td>{{ ($allUserList->currentpage()-1) * $allUserList->perpage() + (1+$key ++) }}</td>
                                                                    <td>{{ $listUser->admitted_date  }}</td>
                                                                    <td>{{ $listUser->userRegistrationId  }}</td>
                                                                    <td>{{ $listUser->name }}</td>
                                                                    <td>{{ $listUser->other_name }}</td>
                                                                    <td>{{ $listUser->gender  }}</td>
                                                                    <td width="100">{{ $listUser->email }}</td>
                                                                    <td>{{ $listUser->telephone }}</td>
                                                                    <td class="hidden-print">
                                                                        @if($listUser->suspend  == 0)
                                                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-backdrop="false" data-target="#editStatusModal{{$listUser->id}}" style="font-size:10px;">Active</button>
                                                                        @else
                                                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-backdrop="false" data-target="#editStatusModal{{$listUser->id}}" style="font-size:10px;">Suspended</button>
                                                                        @endif
                                                                    </td>
                                                                    <td class="hidden-print"><a href="{{ route('viewUser', [$listUser->id]) }}" class="btn btn-sm btn-primary" title="View Student Details"> <i class="fa fa-eye"></i> </a></td>
                                                                    <td class="hidden-print"><a href="{{ route('editTeacher', [$listUser->id]) }}" class="btn btn-sm btn-secondary"> <i class="fa fa-edit"></i> </a></td>
                                                                </tr>

                                                                <!--Change Active/Deactive Student Modal-->
                                                                <div class="modal fade text-left" id="editStatusModal{{$listUser->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-success white">
                                                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-edit"></i> {{ __('Change Status')}}  </h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div>
                                                                                    <div class="text-center"><b> {{ $listUser->student_lastname .' '. $listUser->student_firstname }} </b></div>
                                                                                    <label>Select Student Status</label>
                                                                                    <select id="statusID{{ $listUser->id }}" name="userActive" class="form-control">
                                                                                        <option value="">Select</option>
                                                                                        <option value="0">Active</option>
                                                                                        <option value="1">Suspend</option>
                                                                                    </select>
                                                                                </div>
                                                                            <div class="modal-footer"> 
                                                                                <span id="pleaseWait{{$listUser->id}}" class="text-left"></span>
                                                                                <button type="button" class="btn grey btn-outline-primary" data-dismiss="modal">Close</button>
                                                                                <button type="button" id="{{ $listUser->id }}" class="btn btn-outline-success changeStatus"> Change and Save </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--end Modal-->

                                                                @empty
                                                                <tr>
                                                                    <td colspan="11">
                                                                        <div align="center" class="text-danger"> No User/Teacher Found ! </div>
                                                                   </td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                        <div class="row">
                                                            <div align="right" class="col-xs-12 col-sm-12">
                                                                Showing {{($allUserList->currentpage()-1)*$allUserList->perpage()+1}}
                                                                to {{$allUserList->currentpage()*$allUserList->perpage()}}
                                                                of  {{$allUserList->total()}} entries
                                                                <br />
                                                                <div class="hidden-print">{{ $allUserList->links() }}</div> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>


                                </div>
                            </div><!--//row-->

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
    
        $(".changeStatus").click(function() { 
            var id = this.id;
            $('#pleaseWait' + id).html('').hide();
            var statusID = $("#statusID" + id).val();
            if(id < 0 || id == ''){
                alert('Sorry, we cannot process this operation at this moment. Try again !');
                return false;
            }
            if(statusID < 0 || statusID == ''){
                alert('Sorry, we cannot process this operation at this moment. Try to select from the list again !');
                $("#statusID").focus();
                return false;
            }
            $('#pleaseWait' + id).html('Please wait, processing...').css('color','red').show();
            $.ajax({
                url: "{{url('/')}}" + '/activate-suspend-user/' + id +'/' + statusID,
                type: "get",
                success: function(data){
                    $('#pleaseWait' + id).html(data).css('color','green').fadeIn(1000);
                    $('#editStatusModal' + id).hide();
                    alert(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('An error occurred! Looks like your session has expired or you are not connected to the internet.');
                    $('#pleaseWait' + id).html('').hide();
                }
            });
        });

    });//end document
</script>
@endsection