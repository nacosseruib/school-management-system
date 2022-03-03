@extends('layouts.authLayout') 
@section('pageHeaderTitle') Print Teacher's List @endsection
@section('printTeacherPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          
          
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __("LIST OF USERS/TEACHERS") }}</h4>
                                
                                <hr />
                                <div class="row d-print-none">
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportBasicTeacher', ['type'=>'xls']) }}"><button class="btn btn-info">Export Teacher (xls)</button></a>
                                        </div>
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportBasicTeacher', ['type'=>'xlsx']) }}"><button class="btn btn-info">Export Basic Teacher (xlsx)</button></a>
                                        </div>
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportFullTeacher', ['type'=>'xlsx']) }}"><button class="btn btn-info">Export Full Teacher (xlsx)</button></a>
                                        </div>
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportSTeacherPDF') }}"><button class="btn btn-info">Export Teacher (PDF)</button></a>
                                        </div>
                                    </div><!--//row-->
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
                                                                            <span class="btn btn-sm btn-success" style="font-size:10px;">Active</span>
                                                                        @else
                                                                            <span class="btn btn-sm btn-warning" style="font-size:10px;">Suspended</span>
                                                                        @endif
                                                                    </td>
                                                                    
                                                                </tr>

                                                                
                                                                @empty
                                                                <tr>
                                                                    <td colspan="9">
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
