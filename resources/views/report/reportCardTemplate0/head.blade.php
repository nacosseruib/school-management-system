                        
                            @include('PartialView.schoolHeaderNameLogo', ['titleText'=>"Student report Sheet", 'showLogo'=>1, 'showSlogan'=>1, 'showSchooName'=>1, 'showTitle'=>1, 'showHeader'=>1])
                            <div class="{{$classwatermarkForLogo}} mt-4">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="row"  align="left">
                                            <div class="col-sm-12 font4">
                                                SESSION: <b><b>{{ ($sessionCode) ? strtoupper($sessionCode.' - '.$termName) : '' }}</b></b>
                                            </div>
                                            <div class="col-sm-12 font4">FULL NAME: <b>{{ ($student) ? strtoupper($student->student_lastname .' '. $student->student_firstname) : '' }}</b></div>
                                            <div class="col-sm-12 font4">GENDER: <b>{{ ($student) ? strtoupper($student->student_gender) : '' }}</b></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5" align="right">
                                            <div class="col-sm-12 font4">REGISTRATION NO.: <b>{{ ($student) ? strtoupper($student->student_regID) : '' }}</b></div>
                                            <div class="col-sm-12 font4">CLASS: <b>{{ ($student) ? strtoupper($student->class_name) : '' }}</b></div>
                                            <div class="col-sm-12 font4">NO. ON ROLL: <b>{{ ($student) ? strtoupper($student->student_roll) : '' }}</b></div>
                                    </div>
                                </div>