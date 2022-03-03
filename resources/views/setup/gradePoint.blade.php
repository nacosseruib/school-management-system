@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('Grade Point :: '. Auth::user()->name)) }} @endsection
@section('gradePointPageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">
          @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
          <form  id="submitGradePointForm" class="form d-print-none" method="post" action="{{route('saveGradePoint')}}">
          @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="from-actions-multiple">{{ __('CREATE GRADE POINT') }}</h4>
                            <hr />
                            <div class="alert alert-secondary text-info text" role="alert">
                                <strong> SET UP SCHOOL GRADE POINT FOR RESULT COMPUTATION</strong>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label> Total Mark For  <span class="text-danger"><b>*</b></span> </label>
                                    <select class="form-control" required name="gradeFor" id="gradeFor">
                                        <option value="{{ ((isset($grade)) ? $grade->grade_for : '') }}"> {{ ((isset($grade)) ? $grade->grade_for : 'Select') }} </option>
                                        @for($start = 1; $start <= 100; $start ++)
                                            <option value="{{ $start }}" @if($start == 100) selected @endif> {{ $start .' Marks' }} </option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('gradeFor'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('gradeFor') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label> {{ __('Mark From') }} <span class="text-danger"><b>*</b></span> </label>
                                    <input type="text" autofocus value="{{ ((isset($grade)) ? $grade->mark_from : old('markFrom')) }}" class="form-control" required name="markFrom" id="markFrom" placeholder="E.g 86 ">
                                    @if ($errors->has('markFrom'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('markFrom') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label> {{ __('Mark To') }} <span class="text-danger"><b>*</b></span> </label>
                                    <input type="text" value="{{ ((isset($grade)) ? $grade->mark_to : old('markTo')) }}" class="form-control" required name="markTo" id="markTo" placeholder="E.g  100 ">
                                    @if ($errors->has('markTo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('markTo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row">
                                <div class="col-md-4 mt-2">
                                    <label> Make Grade Active  <span class="text-danger"><b>*</b></span> </label>
                                    <select class="form-control" required name="makeGradeActive" id="makeGradeActive">
                                        <option value="{{ ((isset($grade)) ? $grade->active : '') }}" selected> {{ ((isset($grade)) ? ($grade->active ? 'Active' : 'Not Active') : 'Select') }} </option>
                                        <option value="1">Active</option>
                                        <option value="0">Not Active</option>
                                    </select>
                                    @if ($errors->has('makeGradeActive'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('makeGradeActive') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label> Grade Point <span class="text-danger"><b>*</b></span> </label>
                                    <select class="form-control" required name="gradePoint" id="gradePoint">
                                        <option value="{{ ((isset($grade)) ? $grade->grade_point_remark : '') }}" Selected> {{ ((isset($grade)) ? $grade->grade_point_remark : 'Eslect') }} </option>
                                        <option>A1+</option>
                                        <option>A1</option>
                                        <option>B2</option>
                                        <option>B3</option>
                                        <option>C4</option>
                                        <option>C5</option>
                                        <option>C6</option>
                                        <option>D7</option>
                                        <option>E8</option>
                                        <option>F9</option>
                                        @for($start = 'A'; $start <= 'H'; $start ++)
                                            <option value="{{ $start }}"> {{ $start }} </option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('gradePoint'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('gradePoint') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label> Grade Remark <span class="text-danger"><b>*</b></span> </label>
                                    <input type="text" value="{{ ((isset($grade)) ? $grade->grade_remark : old('gradeRemark')) }}" class="form-control gradeRemark" required list="gradeRemark" name="gradeRemark" placeholder="Enter or Double click to select">
									<datalist id="gradeRemark">
									  <option value="" selected="selected">Select</option>
										@forelse($allGradeRemark as $remark)
											<option value="{{$remark->grade_remark}}">{{$remark->remark_description . ' Result'}}</option>
										@empty
                                        @endforelse
									</datalist>
                                    @if ($errors->has('gradeRemark'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('gradeRemark') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->
                        
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <label> Class Teacher's Comment <span class="text-danger"><b>*</b></span> </label>
                                    <textarea size="190" required class="form-control" name="classTeacherComment" placeholder="Enter class teacher's comment for this Grade">{{ ((isset($grade)) ? $grade->class_teacher_comment : old('classTeacherComment')) }}</textarea>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label> Principal's Comment <span class="text-danger"><b>*</b></span> </label>
                                    <textarea size="190" required class="form-control" name="principalComment" placeholder="Enter principal's comment for this grade">{{ ((isset($grade)) ? $grade->principal_comment : old('principalComment')) }}</textarea>
                                </div>
                            </div><!--//row-->
                        </div>
                        
                        <div class="card-body">
                            <div class="px-3">
                                    <div class="form-actions top clearfix">
                                        <div class="buttons-group float-right">
                                            <input type="hidden" name="gradeID" value="{{ ((isset($grade)) ? $grade->gradeID : '') }}" />
                                            @if(isset($grade))
                                            <a href="{{ route('cancelEditGrade') }}"  class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Cancel Edit
                                            </a>
                                            @endif
                                            <button  id="checkFields" type="button" type="hidden" data-toggle="modal" data-backdrop="false" data-target="#confirmNewGradePoint" class="btn btn-sm btn-success">
                                                <i class="fa fa-check-square-o"></i> {{ __('Save and Continue') }}
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
                            <th>{{ __('Grade For') }}</th>
                            <th>{{ __('Mark From') }}</th>
                            <th>{{ __('Mark To') }}</th>
                            <th>{{ __('Grade') }}</th>
                            <th>{{ __('Grade Mark') }}</th>
                            <th>{{ __('Active') }}</th>
                            <th>{{ __('Last Updated') }}</th>
                            <th class="d-print-none" colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allGradePoint as $key=>$list)
                        <tr>
                            <th>{{ 1+$key ++ }}</th>
                            <th>{{ __($list->grade_for) }}</th>
                            <th>{{ __($list->mark_from) }}</th>
                            <th>{{ __($list->mark_to) }}</th>
                            <th>{{ __($list->grade_point_remark) }}</th>
                            <th>{{ __($list->grade_remark) }}</th>
                            <th>
                                {!! __(($list->active) ? '<span class="text-success"><small>Active</small></span>' : '<span class="text-warning"><small>Not Active</small></span>' ) !!}
                            </th>
                            <th>{{ __($list->updated_at) }}</th>
                            <th class="d-print-none">
                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#editGrade{{$list->gradeID}}"><i class="fa fa-eye"></i></a>
                            </th>
                            <th class="d-print-none">
                                <a href="{{ route('editGradePoint', ['ID'=>$list->gradeID]) }}"><i class="fa fa-edit"></i></a>
                            </th>
                            <th class="d-print-none">
                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#deleteGrade{{$list->gradeID}}"><i class="fa fa-trash"></i></a>
                            </th>
                        </tr>

                            <!-- Delete Modal -->
                                <div class="modal fade text-left d-print-none" id="deleteGrade{{$list->gradeID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-trash"></i> {{ __('Delete Grade Point')}}  </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">  {{ __('Delete Grade') }} ! </div>
                                                <h5><i class="fa fa-arrow-right"></i> {{ __('Are you sure you want to delete this record?')}} </h5>
                                            <p>
                                                <div class="text-danger text-center"> {{ __('You will not be able to recover this record again !')}} </div>
                                            </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                <a href="{{ route('removeGradePoint', [$list->gradeID])}}" class="btn btn-outline-danger">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end Modal-->

                            <!-- View Modal -->
                            <div class="modal fade text-left" id="editGrade{{$list->gradeID}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success white">
                                            <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-edit"></i> View Grade Point </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <!--//-->
                                            
                                                <div class="row">
                                                    <div class="col-md-4 mt-2 text-center">
                                                        <label> Total Mark For </label>
                                                        <div class="text-center form-control"> {{ __($list->grade_for) }} </div>
                                                    </div>
                                                    <div class="col-md-4 mt-2 text-center">
                                                        <label> Mark From </label>
                                                        <div class="text-center form-control"> {{ __($list->mark_from) }} </div>
                                                    </div>
                                                    <div class="col-md-4 mt-2 text-center">
                                                        <label> Mark To </label>
                                                        <div class="text-center form-control"> {{ __($list->mark_to) }} </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mt-2 text-center">
                                                        <label> Grade Point Remark</label>
                                                        <div class="text-center form-control"> {{ __($list->grade_point_remark) }} </div>
                                                    </div>
                                                    <div class="col-md-6 mt-2 text-center">
                                                        <label> Grade Remark </label>
                                                        <div class="text-center form-control"> {{ __($list->grade_remark) }} </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mt-2 text-left">
                                                        <label> Class Teacher's Comment </label>
                                                        <div class="text-left form-control"> {{ __($list->class_teacher_comment) }} </div>
                                                    </div>
                                                    <div class="col-md-12 mt-2 text-left">
                                                        <label> Principal Comment</label>
                                                        <div class="text-left form-control"> {{ __($list->principal_comment) }} </div>
                                                    </div>
                                                </div>
                                                <!--//-->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!--end Modal-->

                        @empty
                            <tr><td colspan="6" class="text-danger">{{ __('No record found!') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
                
        <!--Confirm operation  Modal -->
        <div class="modal fade text-left d-print-none" id="confirmNewGradePoint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Set New School Grade Point')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5 class="text-primary"><i class="fa fa-arrow-right"></i> {{ __('Setting Grade Point enables Staff to compute accurate result.')}} </h5>
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

        //check Mark-From
        $("#checkFields").click(function() { 
            if(($("#markFrom").val()) == '' || ($("#markFrom").val()) < 0 ){ 
                alert('You have to enter Mark-From (i.e starting score) !');
                $("#markFrom").focus();
                return false;
            }
            //check Mark-To
            if($("#markTo").val() == '' || $("#markTo").val() < 0 || ($("#markTo").val() <= $("#gradeTo").val()) ){
                alert('You have to enter Mark-To (i.e ending score) !');
                $("#markTo").focus();
                return false;
            }
            //check Grade Point Remark 
            if($("#gradePointRemark").val() == ''){
                alert('Select Grade Point Remark from the list !');
                $("#gradePointRemark").focus();
                return false;
            }
            //check Grade Remark 
            if($(".gradeRemark").val() == ''){
                alert('Select Grade Remark from the list by double clicking the field or Enter your remark!');
                $(".gradeRemark").focus();
                return false;
            }
        }); 

        $("#submitForm").click(function() {
            $('#submitGradePointForm').submit();
        }); 
    });//end document
</script>
@endsection