<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentSubject;
use App\Models\ScoreType;
use App\Models\PublicSession;
use App\Models\SchoolProfile;
use App\Models\StudentAttendance;
use App\Models\Mark;
use App\Models\Term;
use Schema;
use Session;
use DB;

class ViewResultFunctionController extends BaseController
{
    //
    public function postStudentReportSheetParametersResult($studentID, $classID, $termID, $publicSessionID)
    {   
       
        $scoreType  = 0;
        $getPublicData = PublicSession::find($publicSessionID);
        //Get Student Information
        Session::put('classIDResult', $classID);
        Session::put('studentIDResult', $studentID);
        Session::put('classNameSetResult', StudentClass::where('classID', $classID)->value('class_name'));
       
        Session::put('termIDResult', $termID);
        $sessionCode = $getPublicData->session_name;
        Session::put('sessionCodeResult', $sessionCode);
        //
        if(($getPublicData->mid_full_term) == ($getPublicData->school_termID))  
        {
            //View Full-Term Result
            Session::put('typeOfResultToPreview', 2);
             Session::put('termNameResult', Term::where('termID', $termID)->value('term_name')); 
        }else{
            //View Mid-Term Result
            Session::put('typeOfResultToPreview', 1);
             Session::put('termNameResult', Term::where('termID', $termID)->value('term_name'). '(Mid Term)' ); 
        }
        Session::put('publicSessionID', $publicSessionID); 
        $getStudentNames = Student::find($studentID);
        Session::put('StudentNameSetResult', $getStudentNames->student_regID .' - '. $getStudentNames->student_lastname .' '. $getStudentNames->student_firstname);
        Session::put('getStudentDetails', $this->pickOneStudent($studentID));
        Session::put('getResultData', $this->presentStudentResult($studentID, $classID, $termID, $sessionCode, $scoreType));

        return;
    }

    //
    public function viewStudentReportSheetResult()
    {   
        $data['studentPath']            = $this->studentImagePath();
        $data['schoolPath']             = $this->schoolImagePath();
        $data['classID']                = Session::get('classIDResult');
        $data['studentID']              = Session::get('studentIDResult');
        $data['classNameSet']           = Session::get('classNameSetResult');
        $data['StudentNameSet']         = Session::get('StudentNameSetResult');
        //dd($data['classID'] . ' '. $data['classNameSet']);
        $data['sessionCode']            = Session::get('sessionCodeResult');
        $data['publicSessionID']        = Session::get('publicSessionID'); 
        $data['termID']                 = Session::get('termIDResult');
        $data['termName']               = Session::get('termNameResult');
        $data['student']                = Session::get('getStudentDetails');
        $data['studentExtra']           = $this->studentExtraCurricular(Session::get('studentIDResult'));
        $data['allStudentForResult']    = $this->allStudentForResult(1);
        $data['allClass']               = $this->getClass(); 
        $data['getPublishedSession']    = $this->getPulishedSession();
        $data['allTerm']                = $this->getTerm();
        $data['GPASummary']             = $this->getAllGradePoint();
        $studentAttendanceRemark        = $this->getStudentAttendancePresentAbsent($data['studentID'], $data['classID'], $data['termID'], $data['sessionCode']);
        $data['studentPresent']         = ($studentAttendanceRemark ? $studentAttendanceRemark->total_present : '-');
        $data['studentAbsent']          = ($studentAttendanceRemark ? $studentAttendanceRemark->total_absent : '-');
        $data['otherCommentAttendance'] = ($studentAttendanceRemark ? $studentAttendanceRemark->comment : '-');
        $data['midFullTermResult']      = Session::get('typeOfResultToPreview');
        $getReturnResult                = Session::get('getResultData');
        //
        if(!is_null($getReturnResult)){
            $data['allSubject']     = $getReturnResult['allSubjectOffered'];
            $data['markTest1']      = $getReturnResult['markTest1'];
            $data['markTest2']      = $getReturnResult['markTest2'];
            $data['markExam']       = $getReturnResult['markExam'];
            $data['markTotal']      = $getReturnResult['markTotal'];
            $data['markGrade']      = $getReturnResult['markGrade'];
            $data['markRemark']     = $getReturnResult['markRemark'];
            $data['markPercentage'] = $getReturnResult['markPercentage']; 
            $data['getSubjectPosition'] = $getReturnResult['getSubjectPosition'];
            $data['getSubjectPositionMid'] = $getReturnResult['getSubjectPositionMid'];
            $data['computedBy']     = $getReturnResult['computedBy'];
            $data['dateComputed']   = $getReturnResult['dateComputed'];
            $data['totalMarkObtainable']    = $getReturnResult['totalMarkObtainable'];
            $data['totalMarkObtained']      = $getReturnResult['totalMarkObtained'];
            $data['cummulativePercentage']  = $getReturnResult['cummulativePercentage'];
            $data['overAllGrade']           = $getReturnResult['overAllGrade'];
            $data['cummulativeRemark']      = $getReturnResult['cummulativeRemark'];
            $data['classTeacherComment']    = $getReturnResult['classTeacherComment'];
            $data['principalComment']       = $getReturnResult['principalComment'];
            //MID-TERM
            $data['markGradeMidTerm']      = $getReturnResult['markGradeMidTerm'];
            $data['markRemarkMidTerm']     = $getReturnResult['markRemarkMidTerm'];
            $data['markPercentageMidTerm']  = $getReturnResult['markPercentageMidTerm'];
            $data['classTeacherCommentMidTerm']  = $getReturnResult['classTeacherCommentMidTerm'];
            $data['principalCommentMidTerm']     = $getReturnResult['principalCommentMidTerm'];
            $data['totalMarkObtainableMidTerm']     = $getReturnResult['totalMarkObtainableMidTerm'];
            $data['totalMarkObtainedMidTerm']       = $getReturnResult['totalMarkObtainedMidTerm'];
            $data['cummulativePercentageMidTerm']   = $getReturnResult['cummulativePercentageMidTerm'];
            $data['overAllGradeMidTerm']            = $getReturnResult['overAllGradeMidTerm'];
            $data['cummulativeRemarkMidTerm']       = $getReturnResult['cummulativeRemarkMidTerm'];
             //PREVIOUS REPORT SHEET 1ST, 2ND AND 3RD
            $data['cummulativePercentage1st']   = $getReturnResult['cummulativePercentage1st'];
            $data['cummulativePercentage2nd']   = $getReturnResult['cummulativePercentage2nd'];
            $data['cummulativePercentage3rd']   = $getReturnResult['cummulativePercentage3rd'];
            $data['cummulativeSessionAverage']  = $getReturnResult['cummulativeSessionAverage'];
            
        }else{
            $data['allSubject']   = array();
            $data['markTest1']      = 0;
            $data['markTest2']      = 0;
            $data['markExam']       = 0;
            $data['markTotal']      = 0;
            $data['markGrade']      = null;
            $data['markRemark']     = null;
            $data['markPercentage'] = 0;
            $data['computedBy']     = null;
            $data['getSubjectPosition'] = 0;
            $data['dateComputed']   = null;
            $data['totalMarkObtainable']    = 0;
            $data['totalMarkObtained']      = 0;
            $data['cummulativePercentage']  = 0;
            $data['overAllGrade']           =null;
            $data['cummulativeRemark']      = 0;
            $data['classTeacherComment']    = null;
            $data['principalComment']       = null;
            //MID-TERM
            $data['markGradeMidTerm']               = null;
            $data['markRemarkMidTerm']              = null;
            $data['markPercentageMidTerm']          = 0;
            $data['classTeacherCommentMidTerm']     = null;
            $data['principalCommentMidTerm']        = null;
            $data['totalMarkObtainableMidTerm']     = 0;
            $data['totalMarkObtainedMidTerm']       = 0;
            $data['cummulativePercentageMidTerm']   = 0;
            $data['overAllGradeMidTerm']            = null;
            $data['cummulativeRemarkMidTerm']       = null;
            //PREVIOUS REPORT SHEET 1ST, 2ND AND 3RD
            $data['cummulativePercentage1st']   = 0.00;
            $data['cummulativePercentage2nd']   = 0.00;
            $data['cummulativePercentage3rd']   = 0.00;
            $data['cummulativeSessionAverage']  = 0.00;
        }

        //Session::put('getAllDetailsPreview', $data);
        $data['schoolDetails'] = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->first();
        //
        $returnedData = $this->templateAndWatermark($data['schoolDetails']->report_sheet_template, $data['schoolDetails']->result_sheet_watermark);
        $data['classWaterMark'] = $returnedData['classWaterMark'];
        $data['templateCode']   = $returnedData['templateCode']; 
        $data['classwatermarkForLogo']   = $returnedData['classwatermarkForLogo']; 
        //
        return $data;
    }
    

}//end class
