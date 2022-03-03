<div style="height: 1300px; margin-top:5px;" class="card {{$classWaterMark}}">
            <div class="card-header">
         <!--Comment student activities-->
         <div class="mt-10">

                    <br />

                    <div class="row">
                        <div class="col-sm-5">
                            <table class="table table_morecondensed table-bordered table-responsive table-condensed">
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="5"><i class="fa fa-pencil"></i> GRADE POINT SUMMARY</th>
                                    </tr>
                                    <tr class="text-center font3">
                                        <th>S/N</th>
                                        <th>Mark</th>
                                        <th>Grade</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($GPASummary as $keyGrade=>$listGPA)
                                    <tr class="font3"> 
                                        <td> {{(1 + $keyGrade)}}</td>
                                        <td> {{$listGPA->mark_from .' - '. $listGPA->mark_to}}</td>
                                        <td> {{$listGPA->grade_point_remark}}</td>
                                        <td> {{$listGPA->grade_remark}}</td>
                                    </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="font3">
                                <table class="table table_morecondensed table-bordered table-responsive table-condensed">
                                    <thead>
                                            <tr class="text-center">
                                                <th colspan="2"><i class="fa fa-calendar"></i> ATTENDANCE</th>
                                            </tr>
                                            <tr class="text-center">
                                                <th>School Count</th>
                                                <th>Frequency</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td class="text-left">No. of Time School Open</td>
                                                <td><div class="form-control text-center" style="border: none;">{{ (isset($schoolDetails) ? $schoolDetails->day_school_open : '') }}</div></td>
                                            </tr>
                                            <tr class="text-center"> 
                                                <td class="text-left">No of Time Present</td>
                                                <td>
                                                    <div class="form-control text-left" style="border: none;">{{ (isset($studentPresent) ? $studentPresent : ' - ') }}</div>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td class="text-left">No of Time Absent</td>
                                                <td>
                                                    <div class="form-control text-left" style="border: none;">{{ (isset($studentAbsent) ? $studentAbsent : ' - ') }}</div>
                                                </td>
                                            </tr>
                                            
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-sm-7 font3">
                    
                        <div class="col-md-12">
                        <table class="table table_morecondensed table-bordered table-responsive table-condensed">
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="5"><i class="fa fa-star"></i> RATING</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Quality</th>
                                        <th>Excellent</th>
                                        <th>Good</th>
                                        <th>Fair</th>
                                        <th>Poor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($studentExtra))
                                    @forelse($studentExtra as $listExtra)
                                    <tr class="text-center">
                                        <td class="text-left font3"><b>{!! $listExtra->curricular_name !!}</b></td>
                                        <td align="center"> 
                                            @if($listExtra->excellent)
                                            <div align="center" class="custom-control custom-checkbox">
                                                <input disabled checked type="checkbox" id="qualityIndex" class="selectStudent custom-control-input" >
                                                <label class="custom-control-label" for="qualityIndex"></label>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($listExtra->good)
                                            <div align="center" class="custom-control custom-checkbox">
                                                <input disabled checked type="checkbox" id="qualityIndex" class="selectStudent custom-control-input" >
                                                <label class="custom-control-label" for="qualityIndex"></label>
                                            </div> 
                                            @endif
                                        </td>
                                        <td> 
                                            @if($listExtra->fair)
                                            <div align="center" class="custom-control custom-checkbox">
                                                <input disabled checked type="checkbox" id="qualityIndex" class="selectStudent custom-control-input" >
                                                <label class="custom-control-label" for="qualityIndex"></label>
                                            </div>
                                            @endif
                                        </td>
                                        <td> 
                                            @if($listExtra->poor)
                                            <div align="center" class="custom-control custom-checkbox">
                                                <input disabled checked type="checkbox" id="qualityIndex" class="selectStudent custom-control-input" >
                                                <label class="custom-control-label" for="qualityIndex"></label>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    @endforelse
                                    @endif
                                    
                                    @if($otherCommentAttendance <> null || $otherCommentAttendance <> '-' || $otherCommentAttendance <> '- ')
                                        <tr class="text-center">
                                            <td colspan="5"></td>
                                        </tr>
                                        <tr class="text-center">
                                            <td colspan="5" class="text-center">
                                                <i class="fa fa-comment"></i> <b>NOTE TO PARENT</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-left">
                                                {{ (isset($otherCommentAttendance) ? $otherCommentAttendance : ' - ') }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            </div>

                        </div>
                            <div class="row">
                                <div class="col-md-9 mt-2 mb-2">
                                    <div class="col-sm-12 mt-2 mb-2">
                                        CLASS TEACHER'S COMMENT: <b>{{ $classTeacherCommentMidTerm }}</b> 
                                    </div>
                                    <div class="col-sm-12 mt-2 mb-2">
                                        PRINCIPAL'S COMMENT: <b>{{ $principalCommentMidTerm }}</b> 
                                    </div>
                                    @if($midFullTermResult != 1)
                                    <div class="col-sm-12 mt-2 mb-2 text-success">
                                        RESUMPTION DATE <br />
                                        <span class="text-left">Day/Boarder: </span> <b> {{ (isset($schoolDetails) ? $schoolDetails->school_resumption_date : '') }}</b>
                                    </div>
                                    @endif
                                </div>
                                <div align="right"  class="col-md-3 mt-2 text-right"> 
                                <div align="center">
                                    <img style="width: 100%; height:60px;" src="{{ (Session::get('getSchoolProfile') ? asset(Session::get('path') . Session::get('getSchoolProfile')->signature) : '') }}" class="img-responsive" alt=" " />
                                    </div>
                                    <hr class="mt-0 mb-0">
                                    Principal's Signature                            
                                </div>
                        </div>
                    </div>
                </div>
                 <!--//end comment-->
                 
                  <!--school details-->
                     @php
                    if(Session::get('getSchoolProfile') and Session::get('path'))
                    {
                        $getSchoolProfile = Session::get('getSchoolProfile');
                        $newPath = Session::get('path');
                    }else{
                        $getSchoolProfile = DB::table('school_profile')->where('id', DB::table('school_profile')->orderBy('id', 'Desc')->value('id'))->where('active', 1)->first();
                        $newPath = "appAssets/schoolLogo/";
                    }
                    @endphp
        
        
                  <div class="row" style="background: #f9f9f9; position: relative; bottom: 0px;">
                    <div class="col-md-5">
                        <span style="color: #999; font-size:12px;">
                            {{ $getSchoolProfile ? strtoupper($getSchoolProfile->registration_no) : ''}} <br />
                            <span class="text-lowercase">{{ $getSchoolProfile ? strtoupper($getSchoolProfile->email) : '' }} </span>
                        </span>
                        <br />
                        <span style="color: #999; font-size:12px;">
                            <b class="text-primary text-lowercase"> {{ $getSchoolProfile ? strtoupper($getSchoolProfile->website) : '' }} </b>
                        </span>
                    </div>
                    <div class="col-md-7">
                        <span class="text-lowercase" style="color: #999; font-size:12px;">
                            {{ $getSchoolProfile ? strtoupper($getSchoolProfile->address ) : ''}}
                        </span>
                    </div>
                  </div>



            </div>
        </div>