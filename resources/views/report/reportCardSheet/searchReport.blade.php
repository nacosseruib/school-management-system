
                            <div class="row offset-md-1">
                                <div class="col-md-5">
                                    <label> {{ __('Select Session') }} </label>
                                    <select class="form-control" required name="schoolSession" id="schoolSession">
                                        @forelse($getPublishedSession as $listPublicSession)
                                            <option value="{{ $listPublicSession->publicSessionID }}" {{ ($listPublicSession->publicSessionID == $publicSessionID) ? 'Selected' : '' }}>{{ $listPublicSession->session_name .' - '. $listPublicSession->school_term }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('schoolSession'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('schoolSession') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <label> {{ __('Select Term') }} <b class="text-danger">*</b> </label>
                                    <select class="form-control" required name="schoolTerm" id="schoolTerm">
                                        @forelse($allTerm as $listTerm)
                                            <option value="{{ $listTerm->termID }}" {{ ($listTerm->termID == $termID) ? 'Selected' : '' }}>{{ $listTerm->term_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('schoolTerm'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('schoolTerm') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div><!--//row-->

                            <div class="row offset-md-1">
                                <div class="col-md-5 mt-2">
                                    <label> {{ __('Select Class') }} <b class="text-danger">*</b> </label>
                                    <select class="form-control" required name="className" id="getClassID">
                                        @forelse($getClassNameFromMark as $class) <!--allClass-->
                                            <option value="{{ $class->classID }}" {{ $class->classID == $classID ? 'Select' : '' }}>{{ __($class->class_name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('className'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('className') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div id="studentNameSearch" class="col-md-5 mt-2">
                                    <label> {{ __('Select Student Name') }} <b class="text-danger">*</b>  </label>
                                    <select class="form-control" required name="studentName" id="studentName">
                                        <option value=""> Select Student </option>
                                    </select>
                                    @if ($errors->has('studentName'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('studentName') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div id="paymentReportSearch" style="display:none" class="col-md-5 mt-2">
                                    <label> {{ __('Select Report Type') }} <b class="text-danger">*</b> </label>
                                    <select class="form-control" name="reportType" id="reportType">
                                        <option value="1"> All Payment </option>
                                        <option value="2"> Paid </option>
                                        <option value="3"> Outstanding Balance </option>
                                        <option value="4"> Debtors (Unpaid) </option>
                                    </select>
                                </div>

                            </div><!--//row-->
                        
                       