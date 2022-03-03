@extends('layouts.authLayout') 
@section('pageHeaderTitle') {{ strToUpper(__('WELCOME :: '. Auth::user()->name)) }} @endsection
@section('homePageActive') active @endsection
@section('content')

        <div class="main-content">
          <div class="content-wrapper">

              <div class="row">
                  
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                </div>
                  
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card bg-primary">
                        <div class="card-body">
                            <div class="card-block pt-2 pb-0">
                                <div class="media">
                                    <div class="media-body white text-left">
                                        <h3 class="font-large-1 mb-0">{{ isset($totalStudent) ? $totalStudent : 0 }}</h3>
                                        <span>Total Student</span>
                                    </div>
                                    <div class="media-right white text-right">
                                        <i class="icon-bulb font-large-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="Widget-line-chart" class="height-75 WidgetlineChart WidgetlineChartShadow mb-3">                   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <div class="card-block pt-2 pb-0">
                                <div class="media">
                                    <div class="media-body white text-left">
                                        <h3 class="font-large-1 mb-0">{{ isset($totalTeacher) ? $totalTeacher : 0 }}</h3>
                                        <span>Total Teacher</span>
                                    </div>
                                    <div class="media-right white text-right">
                                        <i class="icon-pie-chart font-large-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="Widget-line-chart2" class="height-75 WidgetlineChart WidgetlineChartShadow mb-3">                  
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card bg-success">
                        <div class="card-body">
                            <div class="card-block pt-2 pb-0">
                                <div class="media">
                                    <div class="media-body white text-left">
                                        <h3 class="font-large-1 mb-0">{{ isset($totalResultComputed) ? $totalResultComputed : 0 }}</h3>
                                        <span>Result Computed</span>
                                    </div>
                                    <div class="media-right white text-right">
                                        <i class="icon-graph font-large-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="Widget-line-chart3" class="height-75 WidgetlineChart WidgetlineChartShadow mb-3">                   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <div class="card-block pt-2 pb-0">
                                <div class="media">
                                    <div class="media-body white text-left">
                                        <h3 class="font-large-1 mb-0">{{ isset($totalResultViewer) ? $totalResultViewer : 0 }}</h3>
                                        <span>Total Result Viewer</span>
                                    </div>
                                    <div class="media-right white text-right">
                                        <i class="icon-wallet font-large-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="Widget-line-chart4" class="height-75 WidgetlineChart WidgetlineChartShadow mb-3">                    
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--</row>-->
            
            <div class="row match-height">
                <div class="col-xs-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block text-center">
                                <span class="font-large-1 d-block mb-1 text-success">{{ __('3 STEPS') }}</span>
                                <span class="primary font-medium-1">{{ __('Three (3) Steps to complete your result computation') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row match-height">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header text-center pb-0">
                            <span class="font-medium-2 danger">{{ __('Total Available Class') }}</span>
                            <h3 class="font-large-2 danger mt-1">{{ isset($allClass) ? count($allClass) : 0 }} </h3>
                        </div>
                        <div class="card-body">
                            <div align="center" class="height-300 pb-5">
                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#SelectClass">
                                    <div class="roundButtonRed"> 
                                        <b><b>{{ __('Click To Select Class') }}</b></b> 
                                    </div>
                                </a>
                                <br />
                                <span id="showClass" class="text-success"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header text-center pb-0">
                            <span class="font-medium-2 info">{{ __('Total Available Subject') }}</span>
                            <h3 class="font-large-2 mt-1 info"> {{ isset($allSubject) ? count($allSubject) : 0 }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div align="center" class="height-300 pb-5">
                                <a href="javascript:;" data-toggle="modal" data-backdrop="false" data-target="#SelectSubject">
                                    <div class="roundButtonBlue"> 
                                        <b><b>{{ __('Click To Select Subject') }}</b></b> 
                                    </div>
                                </a>
                                <br />
                                <span id="showSubject" class="text-success"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header text-center pb-0">
                            <span class="font-medium-2 success">{{ __('Total Result Computed') }}</span>
                            <h3 class="font-large-2 mt-1 success">{{(isset($totalResultComputed) ? $totalResultComputed : 0)}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div align="center" class="height-250"> 
                                <a href="javascript:;"  class="confirmCheck" data-toggle="modal" data-backdrop="false" data-target="#confirmSelectionModal">
                                    <div class="roundButtonGreen"> 
                                        <b><b>{{ __('Click To Compute Result') }}</b></b> 
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--</row>-->


        <!-- Class Modal -->
        <div class="modal fade text-left" id="SelectClass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Select Class')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5><i class="fa fa-arrow-right"></i> {{ __('Select a class from the list')}} </h5>
                      <p>
                        <select class="form-control" id="classIDVal" name="className">
                            <option value="">Select Class</option>
                            @forelse($allClass as $class)
                                <option value="{{ $class->classID }}">{{ $class->class_name}}</option>
                            @empty
                            @endforelse
                        </select>
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="button" id="getClassValue" class="btn btn-outline-warning" data-dismiss="modal">{{ __('Save and Continue')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Modal -->
        <div class="modal fade text-left" id="SelectSubject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Select Subject')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5><i class="fa fa-arrow-right"></i> {{ __('Select a subject from the list')}} </h5>
                      <p>
                      <select class="form-control" id="subjectIDVal" name="className">
                            <option value="">Select Subject</option>
                            <!--@forelse($allSubject as $subject)
                                <option value="{{ $subject->subjectID }}">{{ $subject->subject_name}}</option>
                            @empty
                            @endforelse-->
                        </select>
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="button" id="getSubjectValue" class="btn btn-outline-info" data-dismiss="modal">{{ __('Save and Continue')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!--end Modal-->

        <!-- Confirm seleted parameters Modal -->
        <div class="modal fade text-left" id="confirmSelectionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel12" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                      <h4 class="modal-title" id="myModalLabel12"><i class="fa fa-tree"></i> {{ __('Confirm Class and Subject')}}  </h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h5><i class="fa fa-arrow-right"></i> {{ __('You are about to enter score mark(s) for:')}} </h5>
                      <p>
                        <select class="form-control" id="scoreTypeVal" name="scoreType">
                            <option value="">Select Your Score Type </option>
                            @forelse($allScoreType as $scoreType)
                                <option value="{{ $scoreType->scoretypeID }}">{{ $scoreType->score_type }}</option>
                            @empty
                            @endforelse
                        </select>
                      </p>
                      <p>
                        <b><div id="selectedClass" class="form-control text-success"></div></b>
                        <b><div id="selectedSubject" class="form-control text-success"></div></b>
                      </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-success" data-dismiss="modal">{{ __('Close')}}</button>
                        <a href="{{ route('createMark') }}" class="btn btn-outline-success">{{ __('Save and Continue')}}</a>
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
    //Select Class
    $("#getClassValue" ).click(function() { 
        var classID = $("#classIDVal").val();
        if(classID < 1){
            alert('Please, select a class !');
            $("#classIDVal").focus();
            return false;
        }
        $.ajax({
            url: "{{url('/')}}" + '/get-class-details-json/'+classID,
            type: "get",
            //data: {'ID': classID, '_token': $('input[name=_token]').val()},
            success: function(data){
               $('#subjectIDVal').empty();
                    $('#subjectIDVal').append($('<option>').text(" Select Subject ").attr('value',""));
                    $.each(data.subject, function(model, list) {
                        $('#subjectIDVal').append($('<option>').text(list.subject_name).attr('value', list.subjectID));
                });
                $('#selectedClass').html(data.className);
                $("#showClass").html(data.className).fadeIn(100);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#selectedClass').html('');
                alert('An error occurred! Looks like your session has expired or you are not connected to the internet.');
            }
        });
    });

    //Select Subject
    $("#getSubjectValue" ).click(function() { 
        var subjectID = $("#subjectIDVal").val();
        if(subjectID < 1){
            alert('Please, select a subject !');
            $("#subjectIDVal").focus();
            return false;
        }
        $.ajax({
            url: "{{url('/')}}" + '/get-subject-details-json/'+subjectID,
            type: "get",
            success: function(data){
                $('#selectedSubject').html(data);
                $("#showSubject").html(data).fadeIn(100);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#selectedSubject').html('');
                alert('An error occurred! Looks like your session has expired or you are not connected to the internet.');
            }
        });
    }); 

    //Confirm class and subject
    $(".confirmCheck" ).click(function() { 
        if($("#classIDVal").val() == ''){
            alert('It seems you have not selected any class !');
            return false;
        }
        if($("#subjectIDVal").val() == ''){
            alert('It seems you have not selected any subject !');
            return false;
        }
        $("#confirmSelectionModal").show();
    }); 

    //select Score Type
    $("#scoreTypeVal" ).change(function() { 
        var scoreTypeID = $("#scoreTypeVal").val();
        if(scoreTypeID < 1){
            alert('Please, select a score type !');
            $("#scoreTypeVal").focus();
            return false;
        }
        $.ajax({
            url: "{{url('/')}}" + '/set-score-type-json/'+scoreTypeID,
            type: "get",
            success: function(){
                //
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('An error occurred! Looks like your session has expired or you are not connected to the internet.');
            }
        });
        
    }); 

});//
</script>
@endsection