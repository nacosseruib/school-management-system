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
                            <h4 class="card-title" id="from-actions-multiple">LIST OF All STUDENTS</h4>
                              
                            <div class="row">
                                <div class="col-md-12">
                                    <section id="multi-column">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body collapse show">
                                                    <div class="card-block card-dashboard">
                                                        <table class="table table-hover table-striped table-responsive table-condensed">
                                                            <thead style="font-size:15px; padding:5px 10px; background:#d9d9d9;">
                                                                <tr>
                                                                    <th>S/N</th>
                                                                    <th>Admitted</th>
                                                                    <th>Reg No.</th>
                                                                    <th>Roll</th>
                                                                    <th>Class</th>
                                                                    <th>Surname</th>
                                                                    <th>Others</th>
                                                                    <th>Gender</th>
                                                                    <th>Phone No.</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:15px;">
                                                                @forelse($allStudentList as $key => $listStudent)
                                                                <tr>
                                                                    <td>{{ (1+$key ++) }}</td>
                                                                    <td>{{ $listStudent->admitted_date }}</td>
                                                                    <td>{{ $listStudent->student_regID }}</td>
                                                                    <td>{{ $listStudent->student_roll }}</td>
                                                                    <td width="100">{{ $listStudent->class_name }}</td>
                                                                    <td>{{ $listStudent->student_lastname }}</td>
                                                                    <td>{{ $listStudent->student_firstname }}</td>
                                                                    <td>{{ $listStudent->student_gender }}</td>
                                                                    <td>{{ $listStudent->parent_telephone }}</td>
                                                                </tr>

                                                                
                                                                @empty
                                                                <tr>
                                                                    <td colspan="9">
                                                                        <div align="center" class="text-danger"> No Student Found ! </div>
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
                    </div><!--//card-->
                </div><!--col-12-->
            </div><!--//row-->
            
                
    </div><!--end content-wrapper-->
</div><!--end main content-->

</body>
</html>
