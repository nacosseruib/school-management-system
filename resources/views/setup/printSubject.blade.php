@extends('layouts.authLayout') 
@section('pageHeaderTitle') Print Subject @endsection
@section('printSubjectPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('LIST OF SUBJECT') }}</h4>
                            
                            <hr />
                            <div class="row d-print-none">
                                    <div class="form-group col-sm-4 mb-2">
                                        <a href="{{ route('exportSubject', ['type'=>'xls']) }}"><button class="btn btn-info">Export Subject (xls)</button></a>
                                    </div>
                                    <div class="form-group col-sm-4 mb-2">
                                        <a href="{{ route('exportSubject', ['type'=>'xlsx']) }}"><button class="btn btn-info">Export Subject (xlsx)</button></a>
                                    </div>
                                    <div class="form-group col-sm-4 mb-2">
                                        <a href="{{ route('exportSubject', ['type'=>'pdf']) }}"><button class="btn btn-info">Export Subject (PDF)</button></a>
                                    </div>
                                </div><!--//row-->
                            <hr />
                       
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
                                    <th>{{ __('Created On') }}</th>
                                    <th colspan="2"></th>
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
                                    <th>{{ __($list->subjectDate) }}</th>
                                </tr>

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
        </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection