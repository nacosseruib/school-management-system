                        
                @include('PartialView.operationCallBackAlert', ['showError'=>1, 'showMessage'=>1, 'showAlert'=>1])
                <form class="form" method="post" action="{{route('searchStudentFeePayment')}}">
                @csrf
                        <div class="row offset-md-4 mb-3">
                            <div class="col-md-6"> 
                                <select class="form-control" required name="searchStudentType" id="searchStudentType">
                                    <option value="1" {{ (1 == Session::get("getStudentTypeToSearch")) ? "selected" : (Session::get("getStudentTypeToSearch") == null ? 'selectd' : '') }}> Search All Current Students </option>
                                    <option value="2" {{ (2 == Session::get("getStudentTypeToSearch")) ? "selected" : ''}}> Search All Old or Graduate Students </option>
                                </select>
                            </div>
                        </div>

                        <div>
                            
                            @if(Session::get("getStudentTypeToSearch") == 2)

                                <!--Search old student-->
                                @include('report.reportCardSheet.searchReport')

                            @else
                                <!--Search current student-->
                                <div class="row offset-md-3">
                                        <div class="col-md-5 mt-2">
                                            <label> {{ __('Select Class') }} <b class="text-danger">*</b> </label>
                                            <select class="form-control" required name="className" id="getClassID">
                                                <option value=""> Select Class </option>
                                                @if(isset($allClass))
                                                    @forelse($allClass as $class)
                                                        <option value="{{ $class->classID }}" {{(Session::get('classIDFee') == $class->classID ? 'selected' : '') }}>{{ __($class->class_name) }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @if ($errors->has('className'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('className') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <label> {{ __('Term') }} <b class="text-danger">*</b> </label> 
                                            <select class="form-control" required name="schoolTerm" id="schoolTerm">
                                                <option value=""> Select Term </option>
                                                @if(isset($allTerm))
                                                    @forelse($allTerm as $term)
                                                        <option value="{{ $term->termID }}" {{(Session::get('schoolTerm') == $term->termID ? 'selected' : '') }} >{{($term->term_name) }}</option>
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @if ($errors->has('schoolTerm'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('schoolTerm') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div><!--//row-->
                                    <div class="row offset-md-3">

                                        <div id="studentNameSearch" class="col-md-8 mt-2">
                                            <label> {{ __('Select Student Name') }} <b class="text-danger">*</b> </label>
                                            <select class="form-control" required name="studentName" id="studentName">
                                                <option value="" > Select Student </option>
                                            </select>
                                            @if ($errors->has('studentName'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('studentName') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div id="paymentReportSearch" style="display:none" class="col-md-8 mt-2">
                                            <label> {{ __('Select Report Type') }} <b class="text-danger">*</b> </label>
                                            <select class="form-control" name="reportType" id="reportType">
                                                <option value="1" {{Session::get('reportType') == 1 ? 'Selected' : ''}}> All Payment </option>
                                                <option value="2" {{Session::get('reportType') == 2 ? 'Selected' : ''}}> Complete Payment </option>
                                                <option value="3" {{Session::get('reportType') == 3 ? 'Selected' : ''}}> Outstanding Payment </option>
                                                <option value="4" {{Session::get('reportType') == 4 ? 'Selected' : ''}}> Debtors (Unpaid) </option>
                                            </select>
                                        </div>

                                    </div><!--//row-->
                                @endif

                                <div class="card-body">
                                        <div class="px-3">
                                            <div class="form-actions top clearfix">
                                                <div class="buttons-group text-center">
                                                    <button type="submit" class="btn btn-raised btn-primary mr-1">
                                                        <i class="fa fa-search"></i> {{ __('Search') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                        </div>
                        <input type="text" id="paymentReport" name="paymentReport" value="0" style="display:none"/>
                </form>	