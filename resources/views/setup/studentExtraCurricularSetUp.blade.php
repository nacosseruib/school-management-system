@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Student Quality Setup:: '. Auth::user()->name)) }} @endsection
@section('studentExtraSetup') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

                <div class="card d-print-none">
                    <div class="card-header">
                        <h4 class="card-title" id="from-actions-multiple">{{ __("SEARCH STUDENTS") }}</h4>
                        <hr />
                        @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                        <form class="form" method="post" action="{{route('searchStudentQuality')}}">
                        @csrf
                        <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Class') }} </label>
                                    <select class="form-control" required name="className" id="getClassID">
                                        <option value=""> Select Class </option>
                                        <option value="All"> All classes </option>
                                        @forelse($allClass as $class)
                                            <option value="{{ $class->classID }}">{{ __($class->class_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('className'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('className') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Student Name') }} </label>
                                    <select class="form-control" required name="studentName" id="studentName">
                                        <option value=""> Select Student </option>
                                    </select>
                                    @if ($errors->has('studentName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('studentName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group text-center">
                                            <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                <i class="fa fa-check-square-o"></i> {{ __('Search') }}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </form>	
                </div>
            </div>

            
            <form class="form" method="post" action="{{route('poststudentQualitySetUp')}}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('STUDENT QUALITY RATING SETUP') }}</h4>
                            <hr />

                            <div class="row">

                                <div align="center" class="col-md-12">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update</button>
                                </div>

                                <div align="center" class="col-md-12">
                                    <table class="table table-hover table-stripped table-responsive table-condensed"> 
                                    <thead>
                                        <tr style="background:#d9d9d9">
                                            <th>{{ __('S/N') }}</th>
                                            <th>{{ __("Student's Name") }}</th>
                                            <th>{{ __('Class') }}</th>
                                            <th>{{ __('Gender') }}</th>
                                            <th>{{ __('Quanlity') }}</th>
                                            <th>{{ __('Excellent') }}</th>
                                            <th>{{ __('Good') }}</th>
                                            <th>{{ __('Fair') }}</th>
                                            <th>{{ __('Poor') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($student))
                                        @forelse($student as $key=>$listStudent)
                                        <tr>
                                            <td>{{($student->currentpage()-1) * $student->perpage() + (1 + $key ++)}}</td>
                                            <td align="left"> {{ $listStudent->student_lastname .' '. $listStudent->student_firstname}} </td>
                                            <td> {{ $listStudent->class_name}} </td>
                                            <td> {{ $listStudent->student_gender}} </td>
                                            <td class="text-success"><b><small>{{ $listStudent->curricular_name}}</small></b></td>
                                                 <input type="hidden" value="{{ $listStudent->studentID }}" name="studentID[]" />
                                                 <input type="hidden" value="{{ $listStudent->student_extraID }}" name="qualityID[]" />
                                            <td style="background:#f9f9f9;"> <input type="hidden" value="{{ ($listStudent->excellent) ? 1  : 0 }}" id="excellentValueexcellentItem{{ $listStudent->student_extraID }}" name="excellent[]" />
                                                <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                    <input type="checkbox" value="{{ $listStudent->student_extraID }}" class="custom-control-input excellent" id="excellentItem{{ $listStudent->student_extraID }}" {!! ($listStudent->excellent) ? 'checked'  : '' !!}>
                                                    <label class="custom-control-label" for="excellentItem{{ $listStudent->student_extraID }}"></label>
                                                </div>
                                            </td>
                                            <td style="background:#f9f9f9;"> <input type="hidden" value="{{ ($listStudent->good) ? 1  : 0 }}" id="goodValuegoodItem{{ $listStudent->student_extraID }}" name="good[]" />
                                                <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                    <input type="checkbox" value="{{ $listStudent->student_extraID }}" class="custom-control-input good" id="goodItem{{ $listStudent->student_extraID }}" {!! ($listStudent->good) ? 'checked'  : '' !!}>
                                                    <label class="custom-control-label" for="goodItem{{ $listStudent->student_extraID }}"></label>
                                                </div>
                                            </td>
                                            <td style="background:#f9f9f9;"> <input type="hidden" value="{{ ($listStudent->fair) ? 1  : 0 }}" id="fairValuefairItem{{ $listStudent->student_extraID }}" name="fair[]" />
                                                <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                    <input type="checkbox" value="{{ $listStudent->student_extraID }}" class="custom-control-input fair" id="fairItem{{ $listStudent->student_extraID }}" {!! ($listStudent->fair) ? 'checked'  : '' !!}>
                                                    <label class="custom-control-label" for="fairItem{{ $listStudent->student_extraID }}"></label>
                                                </div>
                                            </td>
                                            <td style="background:#f9f9f9;"> <input type="hidden" value="{{ ($listStudent->poor) ? 1  : 0 }}" id="poorValuepoorItem{{ $listStudent->student_extraID }}" name="poor[]" />
                                                <div align="center" class="custom-control form-control-lg custom-checkbox ml-2">
                                                    <input type="checkbox" value="{{ $listStudent->student_extraID }}" class="custom-control-input poor" id="poorItem{{ $listStudent->student_extraID }}" {!! ($listStudent->poor) ? 'checked'  : '' !!}>
                                                    <label class="custom-control-label" for="poorItem{{ $listStudent->student_extraID }}"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-danger"> No Record found for the selected student ! </td>
                                            </tr>
                                        @endforelse
                                        @endif
                                    </thead>
                                    </table> 
                                </div>
                                <div align="center" class="col-md-12">
                                    <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update</button>
                                </div>
                                <div align="right" class="col-md-12"><hr />
                                    Showing {{($student->currentpage()-1)*$student->perpage()+1}}
                                            to {{$student->currentpage()*$student->perpage()}}
                                            of  {{$student->total()}} entries
                                </div>
                                <div class="d-print-none">{{ $student->links() }}</div>
                            </div><!--//row-->
                    </div>
                </div>
            </div>
        </div>
        </form>	
    </div><!--end content-wrapper-->
</div><!--end main content-->   
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        //Excellent
        $(".excellent" ).click(function() { 
            var getID = this.id; 
            if($(this).prop("checked")) { 
                $('#excellentValue' + getID).val(1);
            }else{ 
                $('#excellentValue' + getID).val(0);
            }
        }); //end function

        //Good
        $(".good" ).click(function() { 
            var getID = this.id; 
            if($(this).prop("checked")) { 
                $('#goodValue' + getID).val(1);
            }else{ 
                $('#goodValue' + getID).val(0);
            }
        }); //end function

        //Excellent
        $(".fair" ).click(function() { 
            var getID = this.id; 
            if($(this).prop("checked")) { 
                $('#fairValue' + getID).val(1);
            }else{ 
                $('#fairValue' + getID).val(0);
            }
        }); //end function

        //Excellent
        $(".poor" ).click(function() { 
            var getID = this.id; 
            if($(this).prop("checked")) { 
                $('#poorValue' + getID).val(1);
            }else{ 
                $('#poorValue' + getID).val(0);
            }
        }); //end function
        
    });//end document
</script>
<!--GET SUBJECT IN CLASS--=== #getClassID === #studentName ===-->
    @include('PartialView.getSudentListWithClassID')
<!--End get subject-->
@endsection