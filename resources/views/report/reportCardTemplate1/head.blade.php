                        
                            @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"Student report Sheet", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                            <div class="col-sm-12 text-center font4">
                                SESSION: <b><b>{{ ($sessionCode) ? strtoupper($sessionCode.' - '.$termName) : '' }}</b></b>
                            </div>
                            <div class="{{$classwatermarkForLogo}}">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-12 font4">FULL NAME: <b>{{ ($student) ? strtoupper($student->student_lastname .' '. $student->student_firstname) : '' }}</b></div>
                                            <div class="col-sm-12 font4">GENDER: <b>{{ ($student) ? strtoupper($student->student_gender) : '' }}</b></div>
                                            <div class="col-sm-12 font4">REGISTRATION NO.: <b>{{ ($student) ? strtoupper($student->student_regID) : '' }}</b></div>
                                            <div class="col-sm-12 font4">CLASS: <b>{{ ($student) ? strtoupper($student->class_name) : '' }}</b></div>
                                            <div class="col-sm-12 font4">NO. ON ROLL: <b>{{ ($student) ? strtoupper($student->student_roll) : '' }}</b></div>
                                            <!--<div class="col-sm-12 font4">REGISTRATION NO.: <b>{{ ($student) ? strtoupper($student->student_regID) : '' }}</b></div>
                                            <div class="col-sm-12 font4">SESSION: <b>{{ ($sessionCode) ? $sessionCode : '' }}</b></div>-->
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div align="right">
                                            <img style="max-width: 90px; max-height: 100px;" src="{{ (($student->photo) ? asset($studentPath . $student->photo) : asset($studentPath .'noPicture.png')) }}" class="img-thumbnail img-responsive" alt=" " />
                                        </div>
                                    </div>
                                </div>