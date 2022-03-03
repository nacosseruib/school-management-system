@extends('layouts.authLayout') 
@section('pageHeaderTitle') Print/Export Student @endsection
@section('printStudentPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __("LIST OF All STUDENTS") }}</h4>
                                
                                <hr />
                                <div class="row d-print-none">
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportBasicStudent', ['type'=>'xls']) }}"><button class="btn btn-info">Export Student (xls)</button></a>
                                        </div>
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportBasicStudent', ['type'=>'xlsx']) }}"><button class="btn btn-info">Export Basic Details (xlsx)</button></a>
                                        </div>
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportFullStudent', ['type'=>'xlsx']) }}"><button class="btn btn-info">Export Full Details (xlsx)</button></a>
                                        </div>
                                        <div class="form-group col-md-3 mb-2">
                                            <a href="{{ route('exportStudentPDF') }}"><button class="btn btn-info">Export Student (PDF)</button></a>
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
                                                            <thead style="font-size:13px; background:#d9d9d9;">
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
                                                                    <th>Email</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:13px;">
                                                                @forelse($allStudentList as $key => $listStudent)
                                                                <tr>
                                                                    <td>{{ ($allStudentList->currentpage()-1) * $allStudentList->perpage() + (1+$key ++) }}</td>
                                                                    <td>{{ $listStudent->admitted_date }}</td>
                                                                    <td>{{ $listStudent->student_regID }}</td>
                                                                    <td>{{ $listStudent->student_roll }}</td>
                                                                    <td width="80">{{ $listStudent->class_name }}</td>
                                                                    <td>{{ $listStudent->student_lastname }}</td>
                                                                    <td>{{ $listStudent->student_firstname }}</td>
                                                                    <td>{{ $listStudent->student_gender }}</td>
                                                                    <td>{{ $listStudent->parent_telephone }}</td>
                                                                    <td>{{ $listStudent->parent_email }}</td>
                                                                </tr>

                                                                
                                                                @empty
                                                                <tr>
                                                                    <td colspan="10">
                                                                        <div align="center" class="text-danger"> No Student Found ! </div>
                                                                   </td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                        <div class="row">
                                                            <div align="right" class="col-xs-12 col-sm-12">
                                                                Showing {{($allStudentList->currentpage()-1)*$allStudentList->perpage()+1}}
                                                                to {{$allStudentList->currentpage()*$allStudentList->perpage()}}
                                                                of  {{$allStudentList->total()}} entries
                                                                <br />
                                                                <div class="hidden-print">{{ $allStudentList->links() }}</div> 
                                                            </div>
                                                        </div>
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

@endsection
