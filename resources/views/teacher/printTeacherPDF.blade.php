<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
</head>
<body>

        <div class="main-content">
          <div class="content-wrapper">
          
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">LIST OF USERS/TEACHERS</h4>
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
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:13px;">
                                                                @forelse($allUserList as $key => $listUser)
                                                                <tr>
                                                                    <td>{{ (1+$key ++) }}</td>
                                                                    <td>{{ $listUser->admitted_date  }}</td>
                                                                    <td>{{ $listUser->userRegistrationId  }}</td>
                                                                    <td>{{ $listUser->name }}</td>
                                                                    <td>{{ $listUser->other_name }}</td>
                                                                    <td>{{ $listUser->gender  }}</td>
                                                                    <td width="100">{{ $listUser->email }}</td>
                                                                    <td>{{ $listUser->telephone }}</td>
                                                                    <td>
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

</body>
</html>