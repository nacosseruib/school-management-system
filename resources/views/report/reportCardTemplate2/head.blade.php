                        
                          
                           
                           <div class="row">
                              <div align="left" class="col-sm-2">
                                  <img style="width: 100px; height: 120px" src="{{ asset(Session::get('path') . Session::get('getSchoolProfile')->logo) }}" class="img-responsive" alt=" " /><!--rounded-circle-->
                              </div>
                              <div align="left" class="col-md-5 ml-0">
                                    <br />
                                    <div class="mt-0 mb-0">
                                        <span style="font-weight:bolder; font-size:20px; color:purple;"><b> <b>
                                            {{ strtoupper(Session::get('getSchoolProfile')->school_full_name) }} 
                                        </b></b></span>
                                        <br />
                                        <span style="font-weight:100; font-size:12px; color:gold;">
                                            <b><i>{{ ucfirst(strtolower(Session::get('getSchoolProfile')->slogan)) }}</i></b>
                                        </span>
                                    </div>
                                    <span style="font-weight:bolder; font-size:12px;">
                                    <b class="text-info">STUDENT REPORT SHEET</b>
                                    </span>
                              </div>
                              <div align="right" class="col-sm-5 mt-3">
                                    <div class="col-sm-12 font4">REGISTRATION NO.: <b>{{ ($student) ? strtoupper($student->student_regID) : '' }}</b></div>
                                    <div class="col-sm-12 font4">CLASS: <b>{{ ($student) ? strtoupper($student->class_name) : '' }}</b></div>
                                    <div class="col-sm-12 font4">NO. ON ROLL: <b>{{ ($student) ? strtoupper($student->student_roll) : '' }}</b></div>
                              </div>
                           </div>

                           <div class="row">
                                    <div class="col-sm-12"> <hr /> </div>
                            </div>


                            <div class="{{$classwatermarkForLogo}}">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-12 font4">SESSION: <b>{{ ($sessionCode) ? strtoupper($sessionCode.' - '.$termName) : '' }}</b></div>
                                            <div class="col-sm-12 font4">FULL NAME: <b>{{ ($student) ? strtoupper($student->student_lastname .' '. $student->student_firstname) : '' }}</b></div>
                                            <div class="col-sm-12 font4">GENDER: <b>{{ ($student) ? strtoupper($student->student_gender) : '' }}</b></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div align="right">
                                            <img style="max-width: 90px; max-height: 100px;" src="{{ (($student->photo) ? asset($studentPath . $student->photo) : asset($studentPath .'noPicture.png')) }}" class="img-thumbnail img-responsive" alt=" " />
                                        </div>
                                    </div>
                                </div>