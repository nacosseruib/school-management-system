@extends('layouts.authLayout') 
@section('pageHeaderTitle') Print/Export Class @endsection
@section('printClassPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('LIST OF CLASS') }}</h4>
                            <hr />

                                <div class="row d-print-none">
                                    <div class="form-group col-sm-4 mb-2">
                                        <a href="{{ route('exportClass', ['type'=>'xls']) }}"><button class="btn btn-info">Export CLass (xls)</button></a>
                                    </div>
                                    <div class="form-group col-sm-4 mb-2">
                                        <a href="{{ route('exportClass', ['type'=>'xlsx']) }}"><button class="btn btn-info">Export Class (xlsx)</button></a>
                                    </div>
                                    <div class="form-group col-sm-4 mb-2">
                                        <a href="{{ route('exportClass', ['type'=>'pdf']) }}"><button class="btn btn-info">Export Class (PDF)</button></a>
                                    </div>
                                </div><!--//row-->
                            <hr />

                        <div  align="center" class="col-md-12">
                            <table class="table table-hover table-stripped table-responsive table-condensed"> 
                            <thead>
                                <tr style="background:#f9f9f9">
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('CLass Code') }}</th>
                                    <th>{{ __('CLass Name') }}</th>
                                    <th>{{ __('CLass Description') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allclass as $key=>$list)
                                <tr>
                                    <th>{{ 1+$key ++ }}</th>
                                    <th>{{ __($list->class_code) }}</th>
                                    <th>{{ __($list->class_name) }}</th>
                                    <th>{{ __($list->description) }}</th> 
                                    <th>{{ __($list->created_at) }}</th>
                                </tr>
                                @empty
                                    <tr><td colspan="5" class="text-danger">{{ __('No record found!') }}</td></tr>
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    
                </div>
            </div>
        </div>
      </div>
    </div><!--end content-wrapper-->
</div><!--end main content-->   

@endsection