<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentClass;
use App\Models\StudentSubject;
use Illuminate\Support\Facades\Mail;
use App\Models\Student; 
use App\Models\StudentExtraCurricular;
use App\Models\ScoreType;
use App\Models\Term;
use App\Models\SchoolSession; 
use App\Models\GradePoint;
use App\Models\GradeRemark;
use App\Models\SchoolProfile;
use App\Models\PublicSession;
use App\Models\StudentAttendance;
use App\Models\Mark;
use Twilio\Rest\Client; 
use App\Models\SMSLog;
use App\Models\Fees;
use App\Models\ClassFeesSetup;
use App\Models\StudentFeeSetup;
use App\Models\StudentPaymentFee;
use App\Models\ClassFeesSetupHistory;
use App\Models\EmailLog;
use App\Mail\SendEmailOut;
use App\User;
use Route;
use Auth;
use Schema;
use Session;
use DB;

class BaseController extends Controller
{
    //Student Photo Path
    public function studentImagePath()
    {
        return "appAssets/passport/student/";
    }

    //Teacher Photo Path
    public function teacherImagePath()
    {
        return "appAssets/passport/user/";
    }

    //school Photo Path
    public function schoolImagePath()
    {
        return "appAssets/schoolLogo";
    }

    //Upload Base path
    public function uploadBasePath()
    {
        //Live
        return "/home/school/School-Eportal/";

        //Local
        //return base_path();
    }

    public function returnSessionCode($sessionID)
    {
        return (PublicSession::find($sessionID) ? PublicSession::find($sessionID)->session_name : ($sessionID == null ? ($this->getSession() ? $this->getSession()->session_code : $sessionID) : $sessionID) ); 
    }

    //school Photo Path
    public function searchAllCurrentOrOldStudent($getID)
    {
        Session::forget('getStudentTypeToSearch');
        Session::put('getStudentTypeToSearch', $getID);
        return redirect()->back();
    }

     //Get all old student parameter
     public function sendOldAndCurrentStudentDataToView()
     {
        $getOldCurrentData['sessionCode']            = null;
        $getOldCurrentData['publicSessionID']        = null; 
        $getOldCurrentData['termID']                 = null;
        $getOldCurrentData['classID']                = null;
        $getOldCurrentData['classNameSet']           = null;
        $getOldCurrentData['allStudentForResult']    = $this->allStudentForResult(1);
        $getOldCurrentData['getPublishedSession']    = $this->getPulishedSession();
        $getOldCurrentData['allTerm']                = $this->getTerm();
        $getOldCurrentData['getClassNameFromMark']   = $this->getAllClassFromMark();
        $getOldCurrentData['allClass']               = $this->getClass(); 

        return $getOldCurrentData;
     }

    //Check If Reg. Number is Auto oe Manual
    public function checkIFRegIDAutoIsOnOff()
    {
        return SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->value('use_auto_reg');
    }

    //get random number
    public function randomUniqueCode()
    {
        return (uniqid().rand().uniqid());
    }

    //Get any Class Name by ID
    public function getClassNameOnly($classID)
    {
        return StudentClass::where('classID', $classID)->value('class_name');
    }
    
    //Get All Active Class
    public function getClass()
    {
        return StudentClass::where('active', 1)->orderBy('classID', 'Asc')->get();
    }

    //Get All Class
    public function getAllClass()
    {
        return StudentClass::orderBy('classID', 'Asc')->get();
    }
    
    //Get single Term name
    public function getTermName($termID)
    {
        $termName = ($termID ? Term::find($termID)->term_name : '');
        return $termName;
    }

    //Get All Subject for student
    public function getSubjectSubmittedForStudent($subjectID)
    { 
        //where('student_subject.active', 1)
        return StudentSubject::join('student_class', 'student_class.classID', '=', 'student_subject.classID')
                ->select('*', 'student_subject.created_at as subjectDate') 
                ->whereIn('student_subject.subjectID', $subjectID)  
                ->orderBy('student_subject.subject_name', 'Asc')      
                ->get();
    }
    
    //Get All Subject for student
    public function getStudentSubject($classID)
    {
        return StudentSubject::where('student_subject.active', 1)
                ->join('student_class', 'student_class.classID', '=', 'student_subject.classID')
                ->select('*', 'student_subject.created_at as subjectDate') 
                ->where('student_class.classID', $classID)  
                ->orderBy('student_subject.subject_name', 'Asc')      
                ->get();
        
    }

    //Get All Active Subject
    public function getSubject()
    {
        return StudentSubject::where('student_subject.active', 1)
                ->join('student_class', 'student_class.classID', '=', 'student_subject.classID')
                ->where('student_class.active', 1)
                ->select('*', 'student_subject.created_at as subjectDate', 'student_subject.active as subjectActive')  
                ->orderBy('student_subject.classID', 'Asc')      
                ->paginate(20);
    }

    //Get All Subject
    public function getAllSubject()
    {
        return StudentSubject::orderBy('student_subject.classID', 'Asc') 
                ->join('student_class', 'student_class.classID', '=', 'student_subject.classID')
                ->select('*', 'student_subject.created_at as subjectDate', 'student_subject.active as subjectActive')       
                ->paginate(20);
    }

    //Get All Extra Curricular
    public function getExtraCurricular()
    {
        return StudentExtraCurricular::where('active', 1)->orderBy('curricularID', 'Desc')->paginate(20);
    }

    //Get Student Extra Curricular
    public function studentExtraCurricular($studentID)
    {
        return DB::table('student_extra')
                    ->join('extra_curricular', 'extra_curricular.curricularID', '=', 'student_extra.extraID')
                    ->where('student_extra.studentID', $studentID)
                    ->where('extra_curricular.active', 1)
                    ->orderBy('student_extra.student_extraID', 'Desc')
                    ->get();
    }

    //Get All Student by Pagination
    public function getStudentQuality($classID, $studentID)
    {
        if($classID <> null and $studentID <> null and ($classID <> 'All') and ($studentID <> 'All'))
        {
            return  DB::table('student_extra')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student_extra.extraID')
                ->leftJoin('student', 'student.studentID', '=', 'student_extra.studentID')
                ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                ->select('*', 'student.created_at as studentDate')  
                ->where('student.active', 1)
                ->where('student.deleted', 0)
                ->where('student.studentID', $studentID)
                ->where('student_class.classID', $classID)
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Asc')    
                ->paginate(30);
        }else{
            return DB::table('student_extra')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student_extra.extraID')
                ->leftJoin('student', 'student.studentID', '=', 'student_extra.studentID')
                ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                ->select('*', 'student.created_at as studentDate')  
                ->where('student.active', 1)
                ->where('student.deleted', 0)
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Asc')     
                ->paginate(30);
        }
    }
    
    /////////ADDING 1ST, 2ND, 3RD AND TH TO NUMBERS
    public function ordinal_suffix($num){
        $num = $num % 100; // protect against large numbers
        if($num < 11 || $num > 13){
             switch($num % 10){
                case 1: return 'st';
                case 2: return 'nd';
                case 3: return 'rd';
            }
        }
        return 'th';
    }
    
    /////////Get Number of Week in a Monthly
    public function weekOfMonth($date) {
        $month = date("n", strtotime($date));
        $year  =  date("Y", strtotime($date));
        $firstday = date("w", mktime(0, 0, 0, $month, 1, $year)); 
        $lastday = date("t", mktime(0, 0, 0, $month, 1, $year)); 
	    if ($firstday!=0) $count_weeks = 1 + ceil(($lastday-8+$firstday)/7);
	    else $count_weeks =  ceil(($lastday-1)/7);
	    //
        return $count_weeks;
    }
    
    
     //Get All Student For Student Attendance
    public function getStudentAttendance($termID, $session)
    {
        if($termID <> null and $session <> null)
        {
            return  StudentAttendance::where('student.deleted', 0)
                ->leftJoin('student', 'student.studentID', '=', 'student_attendance.studentID')
                ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('term', 'term.termID', '=', 'student_attendance.termID')
                ->select('*', 'student_class.class_name as className', 'student_attendance.updated_at as attendanceDate', 'student.studentID as studentIDStudent', 'student_attendance.studentID as studentIDAttd')  
                ->where('student_attendance.session_code', $session)
                ->where('student_attendance.termID', $termID)
                ->orderBy('student_attendance.updated_at', 'Desc')    
                ->paginate(30);
        }else if($termID <> null and $session == null)
        {
            return  StudentAttendance::where('student.deleted', 0)
                ->leftJoin('student', 'student.studentID', '=', 'student_attendance.studentID')
                ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('term', 'term.termID', '=', 'student_attendance.termID')
                ->select('*', 'student_class.class_name as className', 'student_attendance.updated_at as attendanceDate', 'student.studentID as studentIDStudent', 'student_attendance.studentID as studentIDAttd')  
                ->where('student_attendance.termID', $termID)
                ->orderBy('student_attendance.updated_at', 'Desc')    
                ->paginate(30);
        
        }else if($termID == null and $session <> null)
        {
         return  StudentAttendance::where('student.deleted', 0)
                ->leftJoin('student', 'student.studentID', '=', 'student_attendance.studentID')
                ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('term', 'term.termID', '=', 'student_attendance.termID')
                ->select('*', 'student_class.class_name as className', 'student_attendance.updated_at as attendanceDate', 'student.studentID as studentIDStudent', 'student_attendance.studentID as studentIDAttd')  
                ->where('student_attendance.session_code', $session)
                ->orderBy('student_attendance.updated_at', 'Desc')    
                ->paginate(30);
        }else{
            return  StudentAttendance::where('student.deleted', 0)
                ->leftJoin('student', 'student.studentID', '=', 'student_attendance.studentID')
                ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('term', 'term.termID', '=', 'student_attendance.termID')
                ->select('*', 'student_class.class_name as className', 'student_attendance.updated_at as attendanceDate', 'student.studentID as studentIDStudent', 'student_attendance.studentID as studentIDAttd')  
                ->distinct()
                ->orderBy('student_attendance.updated_at', 'Desc')    
                ->paginate(30);
        }
    }

    //VITA ...Get Student Present and Absent Attendance
    public function getStudentAttendancePresentAbsent($studentID, $classID, $termID, $session)
    {
        $data = StudentAttendance::where('studentID', $studentID)
            ->where('classID', $classID)
            ->where('termID', $termID)
            ->where('session_code', $session)
            ->select('total_present', 'total_absent', 'comment')
            ->first();
        return $data;
    }
    
    
     //Get All Student No  Pagination
    public function getAllActiveStudent()
    {
        return Student::where('student.active', 1)->where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')     
                ->get();
    }
    
    
    //Get All Student by Pagination
    public function getStudent()
    {
        return Student::where('student.active', 1)->where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type') 
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')     
                ->paginate(50);
    }


    //All Student in school
    public function allStudentList()
    {
        return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(30);
    }

    //List All Student in school
    public function listAllStudentInSchool($classID, $studentID)
    {
        if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID <> null)
        {
            return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->where('student.studentID', $studentID)
                ->where('student_class.classID', $classID)
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')  
                ->paginate(50);
        }else if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID == null)
        {
            return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->where('student_class.classID', $classID)
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(50);
        }else
        {
            return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(50);
        }
    }
    
    
    
    ////////////////////SEARCH DAILY, WEEKLY, MONTHLY STUDENT///////////////////
    public function searchStudentForDailyWeeklyMonthly($classID, $studentID, $feeID)
    {
        if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID <> null)
        {
           $allStudentList = DB::table('daily_fee_setup')
            ->join('student', 'student.studentID', '=', 'daily_fee_setup.studentID')
            ->join('student_class', 'student_class.classID', '=', 'daily_fee_setup.classID')
            ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->where('daily_fee_setup.termID', ($this->getSession() ? $this->getSession()->termID : null))
            ->where('daily_fee_setup.session_code', ($this->getSession() ? $this->getSession()->session_code : null))
            ->where('daily_fee_setup.classID', $classID)
            ->where('daily_fee_setup.studentID', $studentID)
            ->where('daily_fee_setup.feeID', $feeID)
            ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive') 
            ->orderBy('daily_fee_setup.dailyFeeID', 'Desc')
            ->paginate(30);
        }else if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID == null)
        {
           $allStudentList = DB::table('daily_fee_setup')
            ->join('student', 'student.studentID', '=', 'daily_fee_setup.studentID')
            ->join('student_class', 'student_class.classID', '=', 'daily_fee_setup.classID')
            ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->where('daily_fee_setup.termID', ($this->getSession() ? $this->getSession()->termID : null))
            ->where('daily_fee_setup.session_code', ($this->getSession() ? $this->getSession()->session_code : null))
            ->where('daily_fee_setup.classID', $classID)
            ->where('daily_fee_setup.feeID', $feeID)
            ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive') 
            ->orderBy('daily_fee_setup.dailyFeeID', 'Desc')
            ->paginate(30);
        }else
        {
            $allStudentList = DB::table('daily_fee_setup')
            ->join('student', 'student.studentID', '=', 'daily_fee_setup.studentID')
            ->join('student_class', 'student_class.classID', '=', 'daily_fee_setup.classID')
            ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->where('daily_fee_setup.termID', ($this->getSession() ? $this->getSession()->termID : null))
            ->where('daily_fee_setup.session_code', ($this->getSession() ? $this->getSession()->session_code : null))
            ->where('daily_fee_setup.feeID', $feeID)
            ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive') 
            ->orderBy('daily_fee_setup.dailyFeeID', 'Desc')
            ->paginate(30);
        }
        //
        return $allStudentList;
    }
    
    
    ////////////////////SEARCH DAILY, WEEKLY, MONTHLY REPORT///////////////////
    public function searchDailyWeeklyMonthlyReportPayment($classID, $studentID, $feeID, $date)
    {
        if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID <> null and $feeID <> null)
        {
           $allStudentList = DB::table('daily_fee_setup')
            ->join('student', 'student.studentID', '=', 'daily_fee_setup.studentID')
            ->join('student_class', 'student_class.classID', '=', 'daily_fee_setup.classID')
            ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->where('daily_fee_setup.termID', ($this->getSession() ? $this->getSession()->termID : null))
            ->where('daily_fee_setup.session_code', ($this->getSession() ? $this->getSession()->session_code : null))
            ->where('daily_fee_setup.classID', $classID)
            ->where('daily_fee_setup.studentID', $studentID)
            ->where('daily_fee_setup.feeID', $feeID)
            ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive') 
            ->orderBy('daily_fee_setup.dailyFeeID', 'Desc')
            ->paginate(100);
        }else if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID == null and $feeID <> null)
        {
           $allStudentList = DB::table('daily_fee_setup')
            ->join('student', 'student.studentID', '=', 'daily_fee_setup.studentID')
            ->join('student_class', 'student_class.classID', '=', 'daily_fee_setup.classID')
            ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->where('daily_fee_setup.termID', ($this->getSession() ? $this->getSession()->termID : null))
            ->where('daily_fee_setup.session_code', ($this->getSession() ? $this->getSession()->session_code : null))
            ->where('daily_fee_setup.classID', $classID)
            ->where('daily_fee_setup.feeID', $feeID)
            ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive') 
            ->orderBy('daily_fee_setup.dailyFeeID', 'Desc')
            ->paginate(100);
        }else
        {
            $allStudentList = DB::table('daily_fee_setup')
            ->join('student', 'student.studentID', '=', 'daily_fee_setup.studentID')
            ->join('student_class', 'student_class.classID', '=', 'daily_fee_setup.classID')
            ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->where('daily_fee_setup.termID', ($this->getSession() ? $this->getSession()->termID : null))
            ->where('daily_fee_setup.feeID', $feeID)
            ->where('daily_fee_setup.session_code', ($this->getSession() ? $this->getSession()->session_code : null))
            ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive') 
            ->orderBy('daily_fee_setup.dailyFeeID', 'Desc')
            ->paginate(100);
        }
        //
        return $allStudentList;
    }
    
    
    
    //List All Graduate or Withdrawal Student in school
    public function listAllGraduateWithdrawalStudentInSchool($classID, $studentID)
    {
        if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID <> null)
        {
            return Student::where('student.deleted', 1)->where('student.active', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->where('student.studentID', $studentID)
                ->where('student_class.classID', $classID)
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')  
                ->paginate(50);
        }else if($classID <> 'All' and $studentID <> 'All' and $classID <> null and $studentID == null){
            return Student::where('student.deleted', 1)->where('student.active', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->where('student_class.classID', $classID)
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(50);
        //only graduate
        }else if($classID <> '123456'){
            return Student::where('student.graduate', 1)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(50);
        //only withdraw
        }else if($classID <> '1234567'){
            return Student::where('student.withdraw', 1)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(50);
        }else{
            return Student::orwhere('student.deleted', 1)->where('student.active', 0)->orwhere('student.withdraw', 1)->orwhere('student.graduate', 1)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as newStudentID', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')    
                ->paginate(50);
        }
    }
    

    //All Student for PIN
    public function allActiveStudentForResultPIN()
    {
        return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->where('student.active', 1)
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')   
                ->get();
    }

    //All Student for PIN by CLASS
    public function allActiveStudentByClassForResultPIN($classID)
    {
        return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->where('student.student_class', $classID)
                ->where('student.active', 1)
                ->select('*', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student.student_class', 'Asc')  
                ->orderBy('student.studentID', 'Desc')     
                ->get();
    }


    //All Student in school for Result by JSON
    public function allStudentForResult($classID)
    {
        return Student::where('student.deleted', 0)->where('student.active', 1)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->where('student.student_class', $classID)
                ->select('*', 'student.studentID as studentIDs', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student_lastname', 'Asc')      
                ->get();
    }
    
    //All Student in class for Result from Mark by JSON
    public function allStudentForResultFromMark($classID)
    {
        return Mark::where('mark.classID', $classID)->where('mark.active', 1)
                ->join('student', 'student.studentID', '=', 'mark.studentID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as studentIDs', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->orderBy('student_lastname', 'Asc')  
                ->groupBy('student.studentID')
                ->get();
    }


    //Get Student by ID with all details
    public function pickOneStudent($studentID)
    {
        return Student::where('student.deleted', 0)
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.created_at as studentDate', 'student.active as studentActive')  
                ->where('studentID', $studentID)      
                ->first();
    }

    //Get Student Details only
    public function getStudentDetails($studentID)
    {
        return Student::where('studentID', $studentID) 
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.created_at as studentDate', 'student.active as studentActive')      
                ->first();
    }
    
    
    //Get all Active User/Teacher No Pagination
    public function getAllActiveUser()
    {
        return User::where('users.id', '<>', 1)->where('users.suspend', 0)->where('users.deleted', 0)
                ->leftjoin('teacher', 'teacher.userID', '=', 'users.id')
                ->select('*', 'teacher.updated_at as lastUpdate')  
                ->orderBy('users.id', 'Desc')      
                ->get();
    }
    
    
    //Get all Active User/Teacher
    public function getUser()
    {
        return User::where('users.id', '<>', 1)->where('users.suspend', 0)->where('users.deleted', 0)->where('id', '<>', Auth::user()->id)
                ->leftjoin('teacher', 'teacher.userID', '=', 'users.id')
                ->select('*', 'teacher.updated_at as lastUpdate')  
                ->orderBy('users.id', 'Desc')      
                ->paginate(20);
    }

    //Get all User / Teacher
    public function getUserList()
    {
        return User::where('users.id', '<>', 1)->where('users.deleted', 0)
                ->leftjoin('teacher', 'teacher.userID', '=', 'users.id')
                ->select('*', 'teacher.updated_at as guarantorUpdate')  
                ->orderBy('users.id', 'Desc')      
                ->paginate(20);
    }

    //Get Teacher by ID
    public function pickOneUser($userID)
    {
        return User::where('users.deleted', 0)
                ->leftjoin('role', 'role.roleID', '=', 'users.user_type')
                ->leftjoin('teacher', 'teacher.userID', '=', 'users.id')
                ->select('*', 'teacher.updated_at as guarantorUpdate') 
                ->where('users.id', $userID)      
                ->first();
    }

    //Search Student In Class for list only
    public function getOnlyStudentInClass($classID)
    {
        //get all student in a selected class
        $data['foundStudent'] = Student::where('student.active', 1) 
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as studentsID', 'student.created_at as studentDate') 
                ->where('student.student_class',  $classID) 
                ->orderBy('student.student_lastname', 'Asc')    
                ->get();
        //
        return $data;
    }


    //Search Student  and Mark for computation, update
    public function searchStudentInClass($scoreType, $classID, $subjectID, $termID, $sessionCode)
    {
        $getSubjTotal = StudentSubject::where('subjectID', $subjectID)->selectRaw('sum(max_ca1 + max_ca2 + max_exam) as totalSubjMark')->first();
        $totalSubjectMark = $getSubjTotal->totalSubjMark;
        //get all student in a selected class
        $data['foundStudent'] = Student::where('student.active', 1) 
                ->join('student_class', 'student_class.classID', '=', 'student.student_class')
                ->join('student_subject', 'student_subject.classID', '=', 'student_class.classID')
                ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
                ->select('*', 'student.studentID as studentsID', 'student.created_at as studentDate') 
                ->where('student.student_class',  $classID) 
                ->where('student_subject.subjectID',  $subjectID)
                ->orderBy('student.student_lastname', 'Asc')    
                ->get();
        //
        $markTest1 = array();
        $markTest2 = array();
        $markExam = array();
        $markTotal = array();
        $markGrade = array(); 
        $markRemark = array();
        $markPercentage = array();
        $computedBy = array();
        $dateComputed = array();
        $getMarkID = array();
        if($data['foundStudent'])
        {
            foreach($data['foundStudent'] as $key=>$list){
                //get all marks computed for that selected class
                $getMark = Mark::where('mark.studentID', $list->studentID) 
                    ->leftJoin('users', 'users.id', '=', 'mark.computed_by_ID')
                    ->where('mark.classID', $classID) 
                    ->where('mark.subjectID',  $subjectID) 
                    ->where('mark.termID',  $termID) 
                    ->where('mark.session_code',  $sessionCode)
                    ->select('*', 'mark.updated_at as lastMarkUpdated', 'users.name as computedLastName')    
                    ->first();

                //Test1 (CA1)
                (!empty($getMark) ? $markTest1[$key.$list->studentID] = $getMark->test1 : $markTest1[$key.$list->studentID] = 0);
                //Test2 (CA2)
                (!empty($getMark) ? $markTest2[$key.$list->studentID] = $getMark->test2 : $markTest2[$key.$list->studentID] = 0);
                //Exam
                (!empty($getMark) ? $markExam[$key.$list->studentID] = $getMark->exam : $markExam[$key.$list->studentID] = 0);
                //Total Score
                (!empty($getMark) ? $markTotal[$key.$list->studentID] = ($getMark->test1 + $getMark->test2 +$getMark->exam) : $markTotal[$key.$list->studentID] = 0);
                //Grade
                (!empty($getMark) ? $markGrade[$key.$list->studentID] = $this->gradePoint(($getMark->test1 + $getMark->test2 + $getMark->exam)) : $markGrade[$key.$list->studentID] = 0);
                //Remark
                (!empty($getMark) ? $markRemark[$key.$list->studentID] = $this->gradePointRemark(($getMark->test1 + $getMark->test2 + $getMark->exam)) : $markRemark[$key.$list->studentID] = 0);
                //Percentage (%)
                $calculatePercentage = (($getMark and $totalSubjectMark) ? ((($getMark->test1 + $getMark->test2 +$getMark->exam) / ($totalSubjectMark)) * 100) : 0 );
                (!empty($getMark) ? $markPercentage[$key.$list->studentID] = $calculatePercentage : $markPercentage[$key.$list->studentID] = 0);
                //Computer By
                (!empty($getMark) ? $computedBy[$key.$list->studentID] = $getMark->computedLastName : $computedBy[$key.$list->studentID] = '');
                //get date
                (!empty($getMark) ? $dateComputed[$key.$list->studentID] = $getMark->lastMarkUpdated : $dateComputed[$key.$list->studentID] = '');
                 //get MarkID
                (!empty($getMark) ? $getMarkID[$key.$list->studentID] = $getMark->markID : $getMarkID[$key.$list->studentID] = '');
                
                $key ++;
            }//end foreach
        }
        $data['markTest1']      = $markTest1;
        $data['markTest2']      = $markTest2;
        $data['markExam']       = $markExam;
        $data['markTotal']      = $markTotal;
        $data['markGrade']      = $markGrade;
        $data['markRemark']     = $markRemark;
        $data['markPercentage'] = $markPercentage;
        $data['computedBy']     = $computedBy;
        $data['dateComputed']   = $dateComputed;
        $data['getMarkID']      = $getMarkID;
        //
        return $data;
    }


    public function rankandscore($scores){

       

        return collect($scores)
            ->sortByDesc('score')
            ->zip(range(1, count($scores)))
            ->map(function ($scoreAndRank){
                list($score, $rank) = $scoreAndRank;
                return array_merge($score, [
                    'rank' => $rank
                ]);
            })
            ->groupBy('score')
            ->map(function ($tiedScores){
                $lowestRank = $tiedScores->pluck('rank')->min();
                return $tiedScores->map(function ($rankedScore) use ($lowestRank){
                    return array_merge($rankedScore, [
                        'rank' => $lowestRank,
                    ]);
                });
    
            })
            ->collapse()
            ->sortBy('rank');
        
    
    }



    //Get mark details for student result presentation
    public function getMarkDetails($studentID, $classID, $subjectID, $termID, $sessionCode)
    {
        $getMark = Mark::where('mark.studentID', $studentID) 
            ->leftJoin('users', 'users.id', '=', 'mark.computed_by_ID')
            ->where('mark.classID', $classID) 
            ->where('mark.publish', 1) 
            ->where('mark.subjectID',  $subjectID) 
            ->where('mark.termID',  $termID) 
            ->where('mark.session_code',  $sessionCode)
            ->select('*', 'mark.updated_at as lastMarkUpdated', 'users.name as computedLastName')    
            ->first(); 
        return $getMark;
    }
    
    //check if student took any course per term
    public function checkStudentSubjectTerm($studentID, $classID, $termID, $sessionCode)
    {
        return Mark::where('studentID', $studentID)
                        ->where('classID', $classID)
                        ->where('session_code', $sessionCode)
                        ->where('termID', $termID)
                        ->select('subjectID')
                        ->first();
    }

    
    //START PREPARING REPORT CARD
    public function presentStudentResult($studentID, $classID, $termID, $sessionCode, $scoreType)
    {   
        //
        $markTest1 = array();
        $markTest2 = array();
        $markExam = array();
        $markTotal = array();
        $markGrade = array(); 
        $markRemark = array();
        $markGradeMidTerm = array();
        $markRemarkMidTerm = array();
        $markPercentage = array();
        $markPercentageMidTerm = array();
        $computedBy = array();
        $dateComputed = array();
        $totalMarkObtainableMidTerm  = 0;
        $totalMarkObtainedMidTerm    = 0;
        $totalMarkObtainable = 0;
        $totalMarkObtained   = 0;
        $getSubjectPosition  = array(); 
        $getSubjectPositionMid  = array();
        $totalMarkObtainable1st = 0;
        $totalMarkObtainable2nd = 0;
        $totalMarkObtainable3rd = 0;
        $totalMarkObtained1st = 0;
        $totalMarkObtained2nd = 0;
        $totalMarkObtained3rd = 0;
        $data['cummulativeSessionAverage'] = 0;

        //get all student Subject offered for 1ST TERM
        $subjectID = Mark::where('studentID', $studentID)
            ->where('mark.publish', 1) 
            ->where('classID', $classID)
            ->where('session_code', $sessionCode)
            ->where('termID', $termID)
            ->select('subjectID')
            ->get();
        $data['allSubjectOffered'] = $this->getSubjectSubmittedForStudent(($subjectID ? $subjectID : 0));
        
        
        ///////////////////////////////////////////////////////////////////////
        //get all student Subject offered for 1ST TERM
        $subjectID1ST = Mark::where('studentID', $studentID)
            ->where('mark.publish', 1) 
            ->where('classID', $classID)
            ->where('session_code', $sessionCode)
            ->where('termID', 1)
            ->select('subjectID')
            ->get();
        
        //get all student Subject offered for 2ND TERM
        $subjectID2ND = Mark::where('studentID', $studentID)
            ->where('mark.publish', 1) 
            ->where('classID', $classID)
            ->where('session_code', $sessionCode)
            ->where('termID', 2)
            ->select('subjectID')
            ->get();
        
        //get all student Subject offered for 3RD TERM
        $subjectID3RD = Mark::where('studentID', $studentID)
            ->where('mark.publish', 1) 
            ->where('classID', $classID)
            ->where('session_code', $sessionCode)
            ->where('termID', 3)
            ->select('subjectID')
            ->get();
                        
        $data['allSubjectOffered1st'] = $this->getSubjectSubmittedForStudent(($subjectID1ST ? $subjectID1ST : 0));
        $data['allSubjectOffered2nd'] = $this->getSubjectSubmittedForStudent(($subjectID2ND ? $subjectID2ND : 0));
        $data['allSubjectOffered3rd'] = $this->getSubjectSubmittedForStudent(($subjectID3RD ? $subjectID3RD : 0));
        
        //TOTAL MARK OBTAINABLE & OBTAINED - FIRST TERM
        foreach($data['allSubjectOffered1st'] as $key=>$list1st)
        {
            $totalSubjectMark1st = $list1st->max_ca1 + $list1st->max_ca2 + $list1st->max_exam;
            $getMark1st = $this->getMarkDetails($studentID, $classID, $list1st->subjectID, 1, $sessionCode); 
            $totalMarkObtainable1st += ($totalSubjectMark1st ? $totalSubjectMark1st : 0);
            $totalMarkObtained1st += (($getMark1st) ?  ($getMark1st->test1 + $getMark1st->test2 + $getMark1st->exam) : 0);
        }
        //TOTAL MARK OBTAINABLE & OBTAINED - SECOND TERM
        foreach($data['allSubjectOffered2nd'] as $key=>$list2nd)
        {
            $totalSubjectMark2nd = $list2nd->max_ca1 + $list2nd->max_ca2 + $list2nd->max_exam;
            $getMark2nd = $this->getMarkDetails($studentID, $classID, $list2nd->subjectID, 2, $sessionCode); 
            $totalMarkObtainable2nd += ($totalSubjectMark2nd ? $totalSubjectMark2nd : 0);
            $totalMarkObtained2nd += (($getMark2nd) ?  ($getMark2nd->test1 + $getMark2nd->test2 + $getMark2nd->exam) : 0);
        }
        //TOTAL MARK OBTAINABLE & OBTAINED - THIRD TERM
        foreach($data['allSubjectOffered3rd'] as $key=>$list3rd)
        {
            $totalSubjectMark3rd = $list3rd->max_ca1 + $list3rd->max_ca2 + $list3rd->max_exam;
            $getMark3rd = $this->getMarkDetails($studentID, $classID, $list3rd->subjectID, 3, $sessionCode); 
            $totalMarkObtainable3rd += ($totalSubjectMark3rd ? $totalSubjectMark3rd : 0);
            $totalMarkObtained3rd += (($getMark3rd) ?  ($getMark3rd->test1 + $getMark3rd->test2 + $getMark3rd->exam) : 0);
        } 
        ///////////////////END/////////////////////////////////////////////////
        
        /////
        if($data['allSubjectOffered'])
        {
            //Calculate Total Score and add them to DB for Getting Position
            foreach($data['allSubjectOffered'] as $key=>$list2)
            {   
                $arrSession = explode('/', $sessionCode);
                $scoreSession = $arrSession[0] . $arrSession[1];
                $totalSubjectMarkMidTerm2 = $list2->max_ca1;
                Session::forget('getScoreSession');
                //get mark
                $totalScore = $this->getMarkDetails($studentID, $classID, $list2->subjectID, $termID, $sessionCode); 
                //FULL TIME
                Mark::where('mark.studentID', $studentID) 
                ->where('mark.classID', $classID) 
                ->where('mark.publish', 1) 
                ->where('mark.subjectID',  $list2->subjectID) 
                ->where('mark.termID',  $termID) 
                ->where('mark.session_code',  $sessionCode)
                ->update(['total_score' => (($totalScore) ? $totalScore->exam + $totalScore->test1 + $totalScore->test2 : 0), 'score_session'=> $scoreSession]);
                //MID-TERM
                Mark::where('mark.studentID', $studentID) 
                ->where('mark.classID', $classID) 
                ->where('mark.publish', 1) 
                ->where('mark.subjectID',  $list2->subjectID) 
                ->where('mark.termID',  $termID) 
                ->where('mark.session_code',  $sessionCode)
                ->update(['total_score_mid' => (($totalScore) ? ((($totalScore->test1) / ($totalSubjectMarkMidTerm2)) * 100) : 0), 'score_session'=> $scoreSession]);
            
            }//
            
            foreach($data['allSubjectOffered'] as $key=>$list)
            {
                //Full-Term
                $totalSubjectMark = $list->max_ca1 + $list->max_ca2 + $list->max_exam;
                //Mid-Term
                $totalSubjectMarkMidTerm = $list->max_ca1;

                //get all marks computed for that selected student in that class
                $getMark = $this->getMarkDetails($studentID, $classID, $list->subjectID, $termID, $sessionCode); 
                                
                //FULL TIME
                $sql = 'SELECT total_score, FIND_IN_SET( total_score, (
                    SELECT GROUP_CONCAT( DISTINCT total_score
                    ORDER BY total_score DESC ) FROM mark
                    )
                    ) AS rank
                    FROM mark
                    WHERE studentID =' .$studentID.
                    ' AND classID ='.$classID.
                    ' AND subjectID ='. $list->subjectID.
                    ' AND termID ='.$termID.
                    ' AND publish =1
                    AND score_session ='.($getMark ? $getMark->score_session : $scoreSession);
                $getAll = DB::select($sql);
                //MID TERM
                $sqlMid = 'SELECT total_score_mid, FIND_IN_SET( total_score_mid, (
                        SELECT GROUP_CONCAT( DISTINCT total_score_mid
                        ORDER BY total_score_mid DESC ) FROM mark
                        )
                        ) AS rank
                        FROM mark
                        WHERE studentID =' .$studentID.
                        ' AND classID ='.$classID.
                        ' AND publish =1'.
                        ' AND subjectID ='. $list->subjectID.
                        ' AND termID ='.$termID.
                        ' AND score_session ='.($getMark ? $getMark->score_session : $scoreSession);
                //
                $getAll = DB::select($sql);
                $getAllMidTerm = DB::select($sqlMid);
                //
                ///////////////////end position///////////////
                
                //Test1 (CA1)
                (!empty($getMark) ? $markTest1[$key.$studentID] = $getMark->test1 : $markTest1[$key.$studentID] = 0);
                //Test2 (CA2)
                (!empty($getMark) ? $markTest2[$key.$studentID] = $getMark->test2 : $markTest2[$key.$studentID] = 0);
                //Exam
                (!empty($getMark) ? $markExam[$key.$studentID] = $getMark->exam : $markExam[$key.$studentID] = 0);
                //Total Score
                (!empty($getMark) ? $markTotal[$key.$studentID] = ($getMark->test1 + $getMark->test2 +$getMark->exam) : $markTotal[$key.$studentID] = 0);
                //Grade
                (!empty($getMark) ? $markGrade[$key.$studentID] = $this->gradePoint(($getMark->test1 + $getMark->test2 + $getMark->exam)) : $markGrade[$key.$studentID] = 0);
                //Remark
                (!empty($getMark) ? $markRemark[$key.$studentID] = $this->gradePointRemark(($getMark->test1 + $getMark->test2 + $getMark->exam)) : $markRemark[$key.$studentID] = 0);
                
                //Percentage (%) -Full-Term
                $calculatePercentage = (($getMark and $totalSubjectMark) ? ((($getMark->test1 + $getMark->test2 +$getMark->exam) / ($totalSubjectMark)) * 100) : 0 );
                (!empty($getMark) ? $markPercentage[$key.$studentID] = $calculatePercentage : $markPercentage[$key.$studentID] = 0);
                
                //Percentage (%) - Mid-Term
                $calculatePercentageMidTerm = (($getMark and $totalSubjectMarkMidTerm) ? ((($getMark->test1) / ($totalSubjectMarkMidTerm)) * 100) : 0 );
                (!empty($getMark) ? $markPercentageMidTerm[$key.$studentID] = $calculatePercentageMidTerm : $markPercentageMidTerm[$key.$studentID] = 0);
                //Grade
                (!empty($getMark) ? $markGradeMidTerm[$key.$studentID] = $this->gradePoint($calculatePercentageMidTerm) : $markGradeMidTerm[$key.$studentID] = 0);
                //Remark
                (!empty($getMark) ? $markRemarkMidTerm[$key.$studentID] = $this->gradePointRemark($calculatePercentageMidTerm) : $markRemarkMidTerm[$key.$studentID] = 0);
                
                //Position Full Term 
                (!empty($getMark) ? $getSubjectPosition[$key.$studentID] = (($getAll) ? $getAll[0]->rank : 0)  : $getSubjectPosition[$key.$studentID] = 0);
                //Position MID Term 
                (!empty($getMark) ? $getSubjectPositionMid[$key.$studentID] = (($getAllMidTerm) ? $getAllMidTerm[0]->rank : 0)  : $getSubjectPositionMid[$key.$studentID] = 0);

                //Computer By
                (!empty($getMark) ? $computedBy[$key.$studentID] = $getMark->computedLastName : $computedBy[$key.$studentID] = '');
                //get date
                (!empty($getMark) ? $dateComputed[$key.$studentID] = $getMark->lastMarkUpdated : $dateComputed[$key.$studentID] = '');
                
                //TOTAL MARK OBTAINABLE-MID-TERM
                $totalMarkObtainableMidTerm += (($totalSubjectMarkMidTerm) ? $totalSubjectMarkMidTerm : 0);
                //TOTAL MARK OBTAINED -MIDTERM
                $totalMarkObtainedMidTerm += (($getMark) ?  ($getMark->test1) : 0);
                
                //TOTAL MARK OBTAINABLE -FULL-TERM
                $totalMarkObtainable += (($totalSubjectMark) ? $totalSubjectMark : 0);
                //TOTAL MARK OBTAINED FULL-TERM
                $totalMarkObtained += (($getMark) ?  ($getMark->test1 + $getMark->test2 +$getMark->exam) : 0);
                
                $key ++;
            }//end foreach
        }
        
        $data['markTest1']      = $markTest1;
        $data['markTest2']      = $markTest2;
        $data['markExam']       = $markExam;
        $data['dateComputed']   = $dateComputed;
        $data['computedBy']     = $computedBy;
        //MID-TERM
        $data['markGradeMidTerm']      = $markGradeMidTerm;
        $data['markRemarkMidTerm']     = $markRemarkMidTerm;
        $data['markPercentageMidTerm']          = $markPercentageMidTerm;
        $data['totalMarkObtainableMidTerm']     = $totalMarkObtainableMidTerm;
        $data['totalMarkObtainedMidTerm']       = $totalMarkObtainedMidTerm;
        $data['cummulativePercentageMidTerm']   = ($totalMarkObtainableMidTerm ? (($totalMarkObtainedMidTerm/$totalMarkObtainableMidTerm) * 100) : 0);
        $data['overAllGradeMidTerm']            = ( ($data['cummulativePercentageMidTerm']) ? $this->gradePoint($data['cummulativePercentageMidTerm']) : 0 );
        $data['cummulativeRemarkMidTerm']       = ($data['cummulativePercentageMidTerm'] ? ($this->gradePointRemark($data['cummulativePercentageMidTerm'])) : 0);
        $data['getSubjectPositionMid']          = $getSubjectPositionMid;
        //FULL-TERM
        $data['markTotal']      = $markTotal;
        $data['markGrade']      = $markGrade;
        $data['markRemark']     = $markRemark;
        $data['markPercentage'] = $markPercentage;
        //
        if($termID == 1){
            $totalMarkObtainable = $totalMarkObtainable1st;
            $totalMarkObtained   = $totalMarkObtained1st;
        }else if($termID == 2){
            $totalMarkObtainable = $totalMarkObtainable1st + $totalMarkObtainable2nd;
            $totalMarkObtained   = $totalMarkObtained1st + $totalMarkObtained2nd;
        }else{
            $totalMarkObtainable = $totalMarkObtainable1st + $totalMarkObtainable2nd + $totalMarkObtainable3rd;
            $totalMarkObtained   = $totalMarkObtained1st + $totalMarkObtained2nd + $totalMarkObtained3rd;
        }
        //
        $data['totalMarkObtainable']    = $totalMarkObtainable;
        $data['totalMarkObtained']      = $totalMarkObtained;
        $data['cummulativePercentage']  = ($totalMarkObtainable ? (($totalMarkObtained/$totalMarkObtainable) * 100) : 0);
        $data['overAllGrade']           = (($data['cummulativePercentage']) ? $this->gradePoint($data['cummulativePercentage']) : 0 );
        $data['cummulativeRemark']      = ($data['cummulativePercentage'] ? ($this->gradePointRemark($data['cummulativePercentage'])) : 0);
        $data['getSubjectPosition']     = $getSubjectPosition;
        //
        //CUMMULATIVE SCORE FOR 1ST, 2ND & 3RD - CONVERSION TO PERCENTAGE
        $data['cummulativePercentage1st']  = ($totalMarkObtainable1st ? (($totalMarkObtained1st/$totalMarkObtainable1st) * 100) : 0);
        $data['cummulativePercentage2nd']  = ($totalMarkObtainable2nd ? (($totalMarkObtained2nd/$totalMarkObtainable2nd) * 100) : 0);
        $data['cummulativePercentage3rd']  = ($totalMarkObtainable3rd ? (($totalMarkObtained3rd/$totalMarkObtainable3rd) * 100) : 0);
        //
        $totalCum1st = $data['cummulativePercentage1st'];
        $totalCum2nd = $data['cummulativePercentage2nd'];
        $totalCum3rd = $data['cummulativePercentage3rd'];
        if($termID == 1){ //FIRST TERM PERCENTAGE
            $totalCum = $data['cummulativePercentage1st'];
            $sessionAve = 1;
            $data['cummulativeSessionAverage']  = ($sessionAve ? ($totalCum / $sessionAve) : 0); 
        }else if($termID == 2){ //SECOND TERM PERCENTAGE
            $totalCum = $data['cummulativePercentage1st'] + $data['cummulativePercentage2nd'];
            $sessionAve = 2;
            if($totalCum1st && $totalCum2nd)
            {
                $data['cummulativeSessionAverage']  = ($totalCum ? ($totalCum / 2) : 0);
            }else if(($totalCum1st <= 0) && $totalCum2nd){
                $data['cummulativeSessionAverage']  = ($totalCum ? ($totalCum2nd) : 0);
            }else{
                $data['cummulativeSessionAverage']  = ($sessionAve ? ($totalCum / $sessionAve) : 0); 
            }
        }else{ //THIRD TERM PERCENTAGE
            $totalCum = $data['cummulativePercentage1st'] + $data['cummulativePercentage2nd'] + $data['cummulativePercentage3rd'];
            $sessionAve = 3;
            if($totalCum1st && $totalCum2nd && $totalCum3rd)
            {
                $data['cummulativeSessionAverage']  = ($sessionAve ? ($totalCum / $sessionAve) : 0);
            }else if(($totalCum2nd <= 0) && $totalCum1st && $totalCum3rd)
            {
                $data['cummulativeSessionAverage']  = ($totalCum ? (($totalCum1st + $totalCum3rd) / 2) : 0);
            }else if(($totalCum1st <= 0) && $totalCum2nd && $totalCum3rd)
            {
                $data['cummulativeSessionAverage']  = ($totalCum ? (($totalCum2nd + $totalCum3rd) / 2) : 0);
            }else if(($totalCum1st <= 0) && ($totalCum2nd <= 0) && $totalCum3rd)
            {
                $data['cummulativeSessionAverage']  = ($totalCum ? ($totalCum3rd) : 0);   
            }else{
                $data['cummulativeSessionAverage']  = ($sessionAve ? ($totalCum / $sessionAve) : 0); 
            }
        }
        //END PERCENTAGE CUMUULATIVE
        
        
        //COMMENTS -MID-TERM
        $gradeCommentMidTerm = $this->gradeComment($data['cummulativePercentageMidTerm']);
        $data['classTeacherCommentMidTerm']    = $gradeCommentMidTerm['gradeCommentTeacher'];
        $data['principalCommentMidTerm']       = $gradeCommentMidTerm['gradeCommentPrincipal'];
        //COMMENTS -FULL-TERM
        $gradeComment = $this->gradeComment($data['cummulativePercentage']);
        $data['classTeacherComment']    = $gradeComment['gradeCommentTeacher'];
        $data['principalComment']       = $gradeComment['gradeCommentPrincipal'];
        //
        return $data;
    }

    
    //Score's Type
    public function getScoreType()
	{
		return ScoreType::where('active', 1)->get();
    }

    //Get School Term
    public function getTerm()
	{
		return Term::where('active', 1)->get();
    }
    
    //Get Published Session
    public function getPulishedSession()
	{
        return PublicSession::where('public_session.active', 1)
               //->leftjoin('term', 'term.termID', '=', 'public_session.school_termID')
               ->orderBy('public_session.session_name', 'Desc')
               ->get();
    }


    //Get School current Session
    public function getSession()
	{
        return SchoolSession::where('school_session.active', 1)
               ->join('term', 'term.termID', '=', 'school_session.current_termID')->first();
    }
    
    //Get School Session History
    public function getSchoolSessionHistory()
	{
        $schoolSession = New SchoolSession;
        return $schoolSession::join('term', 'term.termID', '=', 'school_session.current_termID')
                ->orderBy('sessionID', 'Desc')
                ->select('*', 'school_session.active as activeSession')
                ->get();
	}
    
    //Get all grade point
    public function getAllGradePoint()
    {
        return GradePoint::orderBy('mark_from', 'Desc')->get();
    }

    //Grade Remark
    public function getGradeRemark()
    {
        return GradeRemark::where('active', 1)->get();
    }
    
    //get School Profile
    public function schoolProfile()
    {
        return SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('school_profile.active', 1)
            ->leftjoin('registration_format', 'registration_format.reg_formatID', '=', 'school_profile.student_regID_format')
            ->first();
    }


    //Get Grade Point
    public function gradePoint($score)
    {   
        $gradePoint = null;
        if($score >= 0){
            $allGrade = GradePoint::where('active', 1)->select('grade_point_remark', 'mark_from', 'mark_to')->get();
            if($allGrade){
                foreach ($allGrade as $grade) {
                    if (($score >= $grade->mark_from) and ($score <= $grade->mark_to)){
                        $gradePoint = $grade->grade_point_remark;
                        break;
                    }
                }
            }
        }
        return $gradePoint;
    }

    //Get Grade Remark
    public function gradePointRemark($score)
    {   
        $getGradePointRemark = null;
        if($score >= 0){
            $allGrade = GradePoint::where('active', 1)->select('grade_remark', 'mark_from', 'mark_to')->get();
            if($allGrade){
                foreach ($allGrade as $grade) {
                    if (($score >= $grade->mark_from) and ($score <= $grade->mark_to)){
                        $getGradePointRemark = $grade->grade_remark;
                        break;
                    }
                }
            }
        }
        return $getGradePointRemark;
    }

    ////Get Grade Comment
    public function gradeComment($score)
    {   
        $gradeCommentTeacher = null;
        $gradeCommentPrincipal = null;
        if($score >= 0){
            $allGrade = GradePoint::where('active', 1)->select('class_teacher_comment', 'principal_comment', 'mark_from', 'mark_to')->get();
            if($allGrade){
                foreach ($allGrade as $grade) {
                    if (($score >= $grade->mark_from) and ($score <= $grade->mark_to)){
                        $gradeCommentTeacher = $grade->class_teacher_comment;
                        $gradeCommentPrincipal = $grade->principal_comment;
                        break;
                    }
                }
            }
        }
        $gradeComment['gradeCommentTeacher'] = $gradeCommentTeacher;
        $gradeComment['gradeCommentPrincipal'] = $gradeCommentPrincipal;
        return $gradeComment;
    }


    //This function will be called - Template/Watermark
    public function templateAndWatermark($getTemplateCode, $getWatermarkCode)
    {
        //Get result Template
        $template = null;
        $templateCode = 0;
        $watermark = null;
        $watermarkCode = 0;
        $classTemplate = '';
        $classWaterMark = '';
        $classwatermarkForLogo = '';
        if($getTemplateCode ==1){
            $template       = 'Template 1 will be used';
            $templateCode   = 1;
        }elseif($getTemplateCode ==2){
            $template       = 'Template 2 will be used';
            $templateCode   = 2;
        }elseif($getTemplateCode ==3){
            $template       = 'Template 3 will be used';
            $templateCode   = 3;
        }elseif($getTemplateCode ==4){
            $template       = 'Template 4 will be used';
            $templateCode   = 4;
        }else{
            $template       = 'Default will be used';
            $templateCode   = 0;
        }
        $data['template'] = $template; 
        $data['templateCode'] = $templateCode;

        //Get Result Watermark
        if($getWatermarkCode ==1){
            $watermark      = 'Watermark with School Logo';
            $watermarkCode  = 1;
            $classWaterMark = '';
            $classwatermarkForLogo = 'bgImage';
        }elseif($getWatermarkCode ==2){
            $watermark      = 'Watermark with Original';
            $watermarkCode  = 2;
            $classWaterMark = 'bgImageOriginal';
            $classwatermarkForLogo = '';
        }elseif($getWatermarkCode ==3){
            $watermark      = 'Watermark with Not Official';
            $watermarkCode  = 3;
            $classWaterMark = 'bgImageNotOfficial';
            $classwatermarkForLogo = '';
        }elseif($getWatermarkCode ==4){
            $watermark      = 'Watermark with School Logo & Original';
            $watermarkCode  = 4;
            $classWaterMark = 'bgImageOriginal';
            $classwatermarkForLogo = 'bgImage';
        }elseif($getWatermarkCode ==5){
            $watermark      = 'Watermark with School Logo & Not Official';
            $watermarkCode  = 5;
            $classWaterMark = 'bgImageNotOfficial';
            $classwatermarkForLogo = 'bgImage';
        }else{
            $watermark      = 'No Watermark';
            $watermarkCode  = 0;
            $classWaterMark = '';
            $classwatermarkForLogo = '';
        }
        $data['watermark'] = $watermark; 
        $data['watermarkCode'] = $watermarkCode;
        $data['classWaterMark'] = $classWaterMark;
        $data['classwatermarkForLogo'] = $classwatermarkForLogo;
         //
         return $data;
    }


    //PIN Accept 1-36 Only
    private function assign_rand_value($num) {

        // accepts 1 - 36
        switch($num) {
            case "1"  : $rand_value = "a"; break;
            case "2"  : $rand_value = "b"; break;
            case "3"  : $rand_value = "c"; break;
            case "4"  : $rand_value = "d"; break;
            case "5"  : $rand_value = "e"; break;
            case "6"  : $rand_value = "f"; break;
            case "7"  : $rand_value = "g"; break;
            case "8"  : $rand_value = "h"; break;
            case "9"  : $rand_value = "i"; break;
            case "10" : $rand_value = "j"; break;
            case "11" : $rand_value = "k"; break;
            case "12" : $rand_value = "l"; break;
            case "13" : $rand_value = "m"; break;
            case "14" : $rand_value = "n"; break;
            case "15" : $rand_value = "o"; break;
            case "16" : $rand_value = "p"; break;
            case "17" : $rand_value = "q"; break;
            case "18" : $rand_value = "r"; break;
            case "19" : $rand_value = "s"; break;
            case "20" : $rand_value = "t"; break;
            case "21" : $rand_value = "u"; break;
            case "22" : $rand_value = "v"; break;
            case "23" : $rand_value = "w"; break;
            case "24" : $rand_value = "x"; break;
            case "25" : $rand_value = "y"; break;
            case "26" : $rand_value = "z"; break;
            case "27" : $rand_value = "0"; break;
            case "28" : $rand_value = "1"; break;
            case "29" : $rand_value = "2"; break;
            case "30" : $rand_value = "3"; break;
            case "31" : $rand_value = "4"; break;
            case "32" : $rand_value = "5"; break;
            case "33" : $rand_value = "6"; break;
            case "34" : $rand_value = "7"; break;
            case "35" : $rand_value = "8"; break;
            case "36" : $rand_value = "9"; break;
        }
        return $rand_value;
    }
    //PIN Alpha-Numeric only
    public function get_rand_alphanumeric($length) {
        if ($length>0) {
            $rand_id="";
            for ($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return strtoupper($rand_id);
    }
    //PIN Numeric Only
    public function get_rand_numbers($length) {
        if ($length>0) {
            $rand_id="";
            for($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(27,36);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return strtoupper($rand_id);
    }
    //PIN Letter Only
    public function get_rand_letters($length) {
        if ($length>0) {
            $rand_id="";
            for($i=1; $i<=$length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,26);
                $rand_id .= $this->assign_rand_value($num);
            }
        }
        return strtoupper($rand_id);
    }
    
    
    //Call Setup
    public function SMSGateWaySetUp()
    {
        $data['twilioID']       = env('TWILIO_SID');
        $data['twilioToken']    = env('TWILIO_TOKEN');
        $data['twilioFrom']     = env('TWILIO_FROM');
        $data['client']         = new Client( $data['twilioID'], $data['twilioToken']);
        $data['sendFrom']   = $data['twilioFrom'];//(SchoolProfile::first()->phone_no ? SchoolProfile::first()->phone_no : $twilioFrom);
        
        return $data;
    }
    
    //Send SMS
    public function SEND_SMS_WITH_TWILIO_SMS_API($getNumber, $message)
    {
         //call SMS setup
         $twilioID           = env('TWILIO_SID');
         $twilioToken        = env('TWILIO_TOKEN');
         $twilioFrom         = env('TWILIO_FROM');
         $client             = new Client( $twilioID, $twilioToken);
         $sendFrom           = ($this->schoolProfile() ? $this->schoolProfile()->sms_sender_name : 'SchlEportal');
         $senderName         =  substr(strtoupper(str_replace(' ', '', $sendFrom)), 0, 11); //$twilioFrom;
         $message = substr($message, 0, 160);
         $count = 0;
         
         /////Check school SET UP if SMS can be sent/////
        if(($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 1 or ($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 2)
        {
             $arr_recipient = explode(',', $getNumber);
             try{
                foreach ($arr_recipient as $recipient) 
                {
                    $numberLenght = strlen((string)trim($recipient));
                    if($numberLenght == 11)
                    {
                        //Local Number
                        $numberRecipient = '+234' . ltrim(($recipient), '0');
                        try
                        {
                            $client->messages->create(
                                $numberRecipient,
                                [
                                    'from' => $senderName,
                                    'body' => $message,
                                ]
                            );
                            $count++;
                            $this->smsLog($numberRecipient, $message);
                        } catch (Exception $e)
                        {
                            //return; 
                        }//end try
                        
                    }else{
                        //International Number
                        $numberRecipient = ($getNumber);
                        try
                        {
                            $client->messages->create(
                                $numberRecipient,
                                [
                                    'from' => $senderName,
                                    'body' => $message,
                                ]
                            );
                            $count++;
                            $this->smsLog($numberRecipient, $message);
                        } catch (Exception $e)
                        {
                            //return; 
                        }//end try
                    }//if
                }
                return $count;
            } catch (Exception $e)
            {
                return $count;
            }
        }else{
            return $count;
        }
       
    }


    public function SEND_SMS_WITH_EBULK_SMS_API($recipients, $message)
    {
        $json_url = "http://api.ebulksms.com:8080/sendsms.json";
        $xml_url = "http://api.ebulksms.com:8080/sendsms.xml";
        $http_get_url = "http://api.ebulksms.com:8080/sendsms";
        $username = 'nacosseruib@gmail.com';
        $apikey = '3bca33524059c57e253cff7e63176e3662cb7222';
        $sendFrom =($this->schoolProfile() ? $this->schoolProfile()->sms_sender_name : 'SchlEportal');
        $sendername = substr(strtoupper(str_replace(' ', '', $sendFrom)), 0, 10);
        $flash = 0;
        if (get_magic_quotes_gpc()) {
            $message = stripslashes($message);
        }
        $message = substr($message, 0, 160);
        #Use the next line for HTTP POST with JSON
        ///$result = $this->eBULK_SMS_API_HTTP_POST_JSON($json_url, $username, $apikey, $flash, $sendername, $message, $recipients);
        #Uncomment the next line and comment the ones above if you want to use simple HTTP GET
        $result = $this->eBULK_SMS_API_HTTP__GET($http_get_url, $username, $apikey, $flash, $sendername, $message, $recipients);
        return $result;
    }
    
    
    //Function to connect to SMS sending server using HTTP GET
    public function eBULK_SMS_API_HTTP__GET($url, $username, $apikey, $flash, $sendername, $messagetext, $recipients) 
    {
        $query_str = http_build_query(array('username' => $username, 'apikey' => $apikey, 'sender' => $sendername, 'messagetext' => $messagetext, 'flash' => $flash, 'recipients' => $recipients));
        
        //return file_get_contents($url .'?'. $query_str);
        return file_get_contents("{$url}?{$query_str}");
    }
 

    public function eBULK_SMS_API_HTTP_POST_JSON($url, $username, $apikey, $flash, $sendername, $messagetext, $recipients)
    {
            $gsm = array();
            $country_code = '234';
            $arr_recipient = explode(',', $recipients);
            foreach ($arr_recipient as $recipient) {
                $mobilenumber = trim($recipient);
                if (substr($mobilenumber, 0, 1) == '0'){
                    $mobilenumber = $country_code . substr($mobilenumber, 1);
                }
                elseif (substr($mobilenumber, 0, 1) == '+'){
                    $mobilenumber = substr($mobilenumber, 1);
                }
                $generated_id = uniqid('int_', false);
                $generated_id = substr($generated_id, 0, 30);
                $gsm['gsm'][] = array('msidn' => $mobilenumber, 'msgid' => $generated_id);
            }
            $message = array(
                'sender' => $sendername,
                'messagetext' => $messagetext,
                'flash' => "{$flash}",
            );
         
            $request = array('SMS' => array(
                    'auth' => array(
                        'username' => $username,
                        'apikey' => $apikey
                    ),
                    'message' => $message,
                    'recipients' => $gsm
            ));
            $json_data = json_encode($request);
            if ($json_data) {
                $response = $this->EBULK_DOPOSTREQUEST($url, $json_data, array('Content-Type: application/json'));
                $result = json_decode($response); 
                return ($result ? $result->response->status : 0);
            } else {
                return false;
            }
        
    }//end function
    
    
    //Function to connect to SMS sending server using HTTP POST
    public function EBULK_DOPOSTREQUEST($url, $arr_params, $headers = array('Content-Type: application/x-www-form-urlencoded')) 
    {
        $response = array();
        $final_url_data = $arr_params;
        if (is_array($arr_params)) {
            $final_url_data = http_build_query($arr_params, '', '&');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_url_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response['body'] = curl_exec($ch);
        $response['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $response['body'];
    }
    
    


    //SMS Log
    public function smsLog($receiver, $message)
    {
        try
        {
            $smsLog =  New SMSLog;
            $smsLog->receiver = $receiver;
            $smsLog->senderID = Auth::User()->id; 
            $smsLog->sender_name = Auth::User()->name .' '. Auth::User()->other_name;
            $smsLog->message = $message;
            $smsLog->ip_address = \Request::ip();
            $smsLog->location = gethostname();
            $smsLog->route = url()->current();
            $smsLog->last_login = Auth::User()->last_login;
            $smsLog->created_at = date('Y-m-d h:i:sa');
            $smsLog->save();
            return;

        }catch (Exception $e)
        {
            return; //$e->getMessage();
        }
    }
    
    
     //Send Any Email
    public function sendAnyEmail($arrayAllRecipients, $subject, $message, $senderName, $senderEmail)
    {
        /////Check school SET UP if SMS can be sent/////
        if(($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 1 or ($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 3)
        {
            $schoolDetails  = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->select('school_full_name', 'email', 'address', 'phone_no', 'website')->first();
            $emailDetails = ([
                'subject'       => ($subject <> null ? $subject : substr($message, 0, 50) .'...'), 
                'message'       => $message, 
                'senderName'    => $senderName, 
                'senderEmail'   => $senderEmail,
                'schoolFullName' => ($schoolDetails ? $schoolDetails->school_full_name : 'School Eportal'),
                'iPAddress'      => \Request::ip(), 
                'website'      => ($schoolDetails ? $schoolDetails->website : '#'),
            ]);
            
            $count = 0;
            if(is_array($arrayAllRecipients) and is_array($emailDetails))
            {
                foreach($arrayAllRecipients as $emailAddress)
                { 
                    try{
                        if($emailAddress != '')
                        {
                            Mail::to($emailAddress)->send(new SendEmailOut($emailDetails));
                            $count ++;
                            $this->emailLog($emailAddress, $emailDetails);
                        }
                    }catch (\Exception $e) {
        
                    } 
                }
            }
        }
        
        //
        return $count;
    }
    
    //Email Log
    public function emailLog($recipientEmail, $emailDetails)
    {
        try
        {
            $emailLog                 =  New EmailLog;
            $emailLog->sender_email   = $emailDetails['senderEmail'];
            $emailLog->receiver_email = $recipientEmail;
            $emailLog->userID         = Auth::User()->id; 
            $emailLog->sender_name    = $emailDetails['senderName'];
            $emailLog->message        = $emailDetails['message'];
            $emailLog->subject        = $emailDetails['subject'];
            $emailLog->ip_address     = $emailDetails['iPAddress'];
            $emailLog->location       = gethostname();
            $emailLog->route          = url()->current();
            $emailLog->last_login     = Auth::User()->last_login;
            $emailLog->created_at     = date('Y-m-d h:i:sa');
            $emailLog->save();
            return;

        }catch (Exception $e)
        {
            return; //$e->getMessage();
        }
    }
    
    //Process Student Promotion
    public function studentPromotion($arrayStudentID, $studentNewClass)
    { 
        $totalStudentPromoted = 0;
        if(is_numeric($studentNewClass) and ($studentNewClass > 0) and is_array($arrayStudentID) and (count(is_array($arrayStudentID)) > 0))
        {
            foreach($arrayStudentID as $eachStudentID) //
            {
                if($studentNewClass == 123456)
                {
                    $student = Student::find($eachStudentID);
                    $student->active = 0;
                    $student->deleted = 1;
                    $student->updated_at = date('Y-m-d');
                    $student->graduate = 1;
                    $student->save();
                }elseif($studentNewClass == 1234567)
                {
                    $student = Student::find($eachStudentID);
                    $student->active = 0;
                    $student->deleted = 1;
                    $student->updated_at = date('Y-m-d');
                    $student->withdraw = 1;
                    $student->save();
                }else{
                    $student = Student::find($eachStudentID);
                    $student->student_class = $studentNewClass;
                    $student->updated_at = date('Y-m-d');
                    $student->save();
                }
                //
                $totalStudentPromoted ++;
            }
        }else{
            $totalStudentPromoted = 0;
        }
        //
        return $totalStudentPromoted;
    }
    
    //Get Class From Mark For Result
    public function getAllClassFromMark()
    { 
        $getClassFromMarkComputed = DB::table('mark')->where('mark.active', 1)
            ->leftjoin('student_class', 'student_class.classID', '=', 'mark.classID')
            ->select('mark.classID', 'student_class.class_name')
            ->groupBy('classID')
            ->get();
        //
        return $getClassFromMarkComputed;
    }

    //Get All FeesSetup
    public function getAllFees()
    { 
        return Fees::orderBy('fees_name', 'Asc')->where('fees_occurent_type', '<',  5)->paginate(30);
    }

    //Get All Active FeesSetup Paginate
    public function getAllActiveFees()
    { 
        return Fees::where('status', 1)->where('fees_occurent_type', '<',  5)->orderBy('fees_name', 'Asc')->paginate(30);
    }
    
    //Get All Active FeesSetup
    public function activeFees()
    { 
        return Fees::where('status', 1)
        ->leftjoin('term', 'term.termID', '=', 'fees.fees_occurent_type')
        ->where('fees_occurent_type', '<',  5)
        ->orderBy('fees.fees_name', 'Asc')
        ->get();
    }
    
    //Get All Active Daily, Weekly, Monthly FeesSetup
    public function activeDailyFees()
    { 
        return Fees::where('status', 1)
        ->leftjoin('term', 'term.termID', '=', 'fees.fees_occurent_type')
        ->where('fees_occurent_type', '>',  4)
        ->orderBy('fees.fees_name', 'Asc')
        ->get();
    }



    //Get All Active Assigned FeesSetup for student
    public function getAllAssignedFeesSetup($studentID, $classID, $termID)
    { 
        if($termID == 4)
        {
            $data['getAllAssigned'] = Fees::where('class_fees_setup.classID', $classID) 
                ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
                ->Where('fees.core_fee', '<>', 1)
                ->where('fees.fees_occurent_type', '<',  5)
                ->orderBy('fees.fees_name', 'Asc')
                ->get();
        }else{
            $data['getAllAssigned'] = Fees::where('class_fees_setup.classID', $classID) 
                ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
                ->Where('fees.fees_occurent_type', $termID)
                ->Where('fees.core_fee', '<>', 1)
                ->where('fees.fees_occurent_type', '<',  5)
                ->orderBy('fees.fees_name', 'Asc')
                ->get();
        }
        //Get All Active Additional Fee for student
        if($termID == 4)
        {
            $data['getAllAdditionFee'] = StudentFeeSetup::where('student_fees_setup.classID', $classID) 
                ->join('fees', 'fees.feessetupID', '=', 'student_fees_setup.feeID')
                ->Where('student_fees_setup.studentID', $studentID)
                ->Where('student_fees_setup.additional_fee', 1)
                ->orderBy('fees.fees_name', 'Asc')
                ->get();
        }else{
            $data['getAllAdditionFee'] = StudentFeeSetup::where('student_fees_setup.classID', $classID) 
                ->join('fees', 'fees.feessetupID', '=', 'student_fees_setup.feeID')
                ->Where('fees.fees_occurent_type', $termID)
                ->Where('student_fees_setup.studentID', $studentID)
                ->Where('student_fees_setup.additional_fee', 1)
                ->orderBy('fees.fees_name', 'Asc')
                ->get();
        }
        //
        return $data;
    }
    
    //Get All Active Assigned Core FeesSetup for student
    public function getAllAssignedCoreFeesSetup($classID)
    { 
        $getAllAssignedCoreFee =Fees::where('class_fees_setup.classID', $classID) 
        ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
        ->Where('fees.core_fee', 1)
        ->orderBy('fees.fees_name', 'Asc')
        ->groupBy('feessetupID')
        ->get();
        return $getAllAssignedCoreFee;
    }

    /***********************STUDENT FEES SETUP*********************/
    //create student fee setup
    public function getStudentFeesSetupAndBalance($getClassID, $getStudentID, $getTermID, $getSessionID) 
    {  
        $classID            = $getClassID; 
        $studentID          = $getStudentID;
        $termID             = $getTermID; 

        $newCoreFeeAmount = array();
        $newFeeAmount = array();
        $newAdditionalFeeAmount = array();
        $SUMCoreFeeAmount = 0;
        $SUMFeeAmount = 0;
        $SUMAdditionalFeeAmount = 0;
        $getCoreAmonunt = null;
        $getAmonunt = null;
        $getAdditionalAmonunt = null;

        $data['studentImagePath'] = $this->studentImagePath();
        $data['classNameValue']  = $classID;
        $data['className']  = (StudentClass::find($classID) ? StudentClass::find($classID)->class_name : '');
        $data['studentNameValue'] = $studentID;
        $data['schoolTermValue']  = $termID;
        $data['termName']       = ($termID ? $this->getTermName($termID) : ''); 
        $data['schoolTerm']         = $this->getTerm(); 
        $data['schoolSession']      = $this->returnSessionCode($getSessionID);
        $data['allFeesSetup']       = $this->activeFees();

        ($studentID ? $data['studentDetails'] = $this->getStudentDetails($studentID) : $data['studentDetails'] = []);
        $getValue = $this->getAllAssignedFeesSetup($studentID, $classID, $termID);
        ($classID && $studentID ? $data['getAllAssignedFees'] = $getValue['getAllAssigned'] : $data['getAllAssignedFees'] = []);
        ($classID ? $data['getAllAssignedCoreFees'] = $this->getAllAssignedCoreFeesSetup($classID) : $data['getAllAssignedCoreFees'] = []);
        ($classID && $studentID ? $data['getAllAdditionStudentFee'] = $getValue['getAllAdditionFee'] : $data['getAllAdditionStudentFee'] = []); 
        
        //core fee amount
        if($studentID and $data['getAllAssignedCoreFees']){
            foreach($data['getAllAssignedCoreFees'] as $key=>$newCore){
                $getCoreAmonunt = StudentFeeSetup::where('feeID', $newCore->feessetupID)->where('studentID', $studentID)->first();
                $newCoreFeeAmount[$key.$newCore->feessetupID] = ($getCoreAmonunt ? $getCoreAmonunt->amount : $newCore->amount);
                $SUMCoreFeeAmount += ($getCoreAmonunt ? $getCoreAmonunt->amount : $newCore->amount);
            }
        }
        //periodic fee amount
        if($studentID and $data['getAllAssignedFees']){
            foreach($data['getAllAssignedFees'] as $keyP=>$newfee){
                $getAmonunt = StudentFeeSetup::where('feeID', $newfee->feessetupID)->where('studentID', $studentID)->first();
                $newFeeAmount[$keyP.$newfee->feessetupID] = ($getAmonunt ? $getAmonunt->amount : $newfee->amount);
                $SUMFeeAmount += ($getAmonunt ? $getAmonunt->amount : $newfee->amount);
            }
        }
        //Additional fee amount
        if($studentID and $data['getAllAdditionStudentFee']){
            foreach($data['getAllAdditionStudentFee'] as $keyMore=>$newMorefee){
                $getAdditionalAmonunt = StudentFeeSetup::where('feeID', $newMorefee->feessetupID)->where('studentID', $studentID)->where('classID', $classID)->first();
                $newAdditionalFeeAmount[$keyMore.$newMorefee->feessetupID] = ($getAdditionalAmonunt ? $getAdditionalAmonunt->amount : $newMorefee->amount);
                $SUMAdditionalFeeAmount += ($getAdditionalAmonunt ? $getAdditionalAmonunt->amount : $newMorefee->amount);
            }
        }
        $data['newCoreStudentFeeAmount']        = $newCoreFeeAmount;
        $data['newStudentFeeAmount']            = $newFeeAmount;
        $data['newStudentAdditionalFeeAmount']  = $newAdditionalFeeAmount;
        $data['totalAmountDueToBePaidPerStudent'] = $SUMCoreFeeAmount + $SUMFeeAmount + $SUMAdditionalFeeAmount;
        /***********************END STUDENT FEES SETUP*********************/
        //
        return $data;
    }

    //PAY PAYMENT FEE - INSERT
    public function PayFeeInsert($studentID, $classID, $termID, $sessionCode, $description, $paymentDate, $totalAmount, $balance, $amountToPay, $paidByName, $paidByEmail, $paidByPhone, $platformPaidFrom, $paymentStatus, $paymentStatusCode)
    {   
        $studentInfo = ($studentID ? Student::find($studentID) : null);
        $success = 0;
        if(Student::find($studentID) and $classID)
        {
            $payFee                         = new StudentPaymentFee;
            $payFee->session_code           = $sessionCode;
            $payFee->classID                = $classID;
            $payFee->studentID              = $studentID;
            $payFee->termID                 = $termID;
            $payFee->className              = ($classID ? StudentClass::find($classID)->class_name : null);
            $payFee->studentRegID           = ($studentInfo ? $studentInfo->student_regID : null);
            $payFee->studentName            = ($studentInfo ? $studentInfo->student_lastname .' '.  $studentInfo->student_firstname : null);
            $payFee->payment_description    = $description;
            $payFee->payment_date           = ($paymentDate <> null ? $paymentDate : date('Y-m-d'));
            $payFee->payment_time           = date('h:i:sa');  
            $payFee->created_at             = date('Y-m-d');
            $payFee->updated_at             = date('Y-m-d');
            $payFee->total_amount_due       = $totalAmount;
            $payFee->balance_due            = $balance;
            $payFee->amount_paid            = $amountToPay;
            $payFee->paid_by_name           = $paidByName;
            $payFee->paid_by_email          = $paidByEmail;
            $payFee->paid_by_phone          = $paidByPhone;
            $payFee->parent_name            = ($studentInfo ? $studentInfo->parent_lastname .' '. $studentInfo->parent_firstname : null);
            $payFee->parent_email           = ($studentInfo ? $studentInfo->parent_email : null);
            $payFee->parent_phone           = ($studentInfo ? $studentInfo->parent_telephone : null);
            $payFee->platform_paid_from     = $platformPaidFrom;
            $payFee->ip_address             = null;
            $payFee->paid_location          = null;
            $payFee->tokenID                = $this->get_rand_alphanumeric(36);
            $payFee->transactionID          = $this->get_rand_numbers(10);
            $payFee->payment_status         = $paymentStatus;
            $payFee->payment_status_code    = $paymentStatusCode;
            $success                        = $payFee->save();
            if($success)
            {
                //SEND EMAIL AND SMS
                //send sms......
                if(($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 1 or ($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 2)
                {
                    try{
                        if($studentInfo and $amountToPay > 0)
                        {
                            $parentNumber = ($studentInfo ? $studentInfo->parent_telephone : null);
                            $amountPaidNow = number_format($amountToPay, 2);
                            $studentRegNo = ($studentInfo ? $studentInfo->student_regID : '');
                            $studentNamePaid = ($studentInfo ? $studentInfo->student_lastname .' '.  $studentInfo->student_firstname : '');
                            $schoolShortName = ($this->schoolProfile() ? $this->schoolProfile()->school_short_name : 'SchlEPortal');
                            $smsMessage = $schoolShortName .' - Payment Alert: ' . $studentNamePaid .' ('. $studentRegNo .') paid '. $amountPaidNow . ' on ' . $paymentDate  .' Bal.: '. $balance .'. '. $description. ' Thanks';
                            
                            //$this->SEND_SMS_WITH_EBULK_SMS_API($parentNumber, $smsMessage);
                            $this->SEND_SMS_WITH_TWILIO_SMS_API($parentNumber, $smsMessage);
                        }
                    } catch (Exception $e){}
                }//end check
                
                //send Email.....
                if(($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 1 or ($this->schoolProfile() ? $this->schoolProfile()->send_email : 0) == 3)
                {
                    //send Email.....
                    try{
                        //($getPINDetails ? Mail::to('admin@schooleportal.com')->send(new SendSuperAdminEmailWhenPINGenerated($allPINGenerated)) : ''); 
                    }catch (\Exception $e) {}
                }//end check

            }
        }
        //
        return $success;
    }

    //UPDATE PAYMENT FEE - UPDATE
    public function PayFeeUpdate($PaymentFeeID, $studentID, $classID, $termID, $sessionCode, $description, $paymentDate, $totalAmount, $balance, $amountToPay, $paidByName, $paidByEmail, $paidByPhone, $platformPaidFrom, $paymentStatus, $paymentStatusCode)
    {   
        $studentInfo = ($studentID ? Student::find($studentID) : null);
        $success = 0;
        if(Student::find($studentID) and StudentPaymentFee::find($PaymentFeeID))
        {
            $payFee                         = StudentPaymentFee::find($PaymentFeeID);
            $payFee->session_code           = $sessionCode;
            $payFee->classID                = $classID;
            $payFee->studentID              = $studentID;
            $payFee->termID                 = $termID;
            $payFee->className              = ($classID ? StudentClass::find($classID)->class_name : null);
            $payFee->studentRegID           = ($studentInfo ? $studentInfo->student_regID : null);
            $payFee->studentName            = ($studentInfo ? $studentInfo->student_lastname .' '.  $studentInfo->student_firstname : null);
            $payFee->payment_description    = $description;
            $payFee->payment_date           = ($paymentDate <> null ? $paymentDate : date('Y-m-d'));
            $payFee->updated_at             = date('Y-m-d');
            $payFee->payment_time           = date('h:i:sa');
            $payFee->total_amount_due       = $totalAmount;
            $payFee->balance_due            = $balance;
            $payFee->amount_paid            = $amountToPay;
            $payFee->paid_by_name           = $paidByName;
            $payFee->paid_by_email          = $paidByEmail;
            $payFee->paid_by_phone          = $paidByPhone;
            $payFee->parent_name            = ($studentInfo ? $studentInfo->parent_lastname .' '. $studentInfo->parent_firstname : null);
            $payFee->parent_email           = ($studentInfo ? $studentInfo->parent_email : null);
            $payFee->parent_phone           = ($studentInfo ? $studentInfo->parent_telephone : null);
            $payFee->platform_paid_from     = $platformPaidFrom;
            $payFee->ip_address             = null;
            $payFee->paid_location          = null;
            $payFee->payment_status         = $paymentStatus;
            $payFee->payment_status_code    = $paymentStatusCode;
            $success                        = $payFee->save();
        }
        //
        return $success;
    }

    //GET TOTAL AMOUNT STUDENT NEED TO PAID PER SESSION AND TERM
    public function totalStudentFeeToBePaidANDPaidANDBalance($studentID, $classID, $termID, $getSessionID)
    {   
        $getFeeAssigned     = $this->getAllAssignedFeesSetup($studentID, $classID, $termID);
        $getCoreFeeAssigned = $this->getAllAssignedCoreFeesSetup($classID);

        $data['getAllAssignedFees']         = ($classID && $studentID ? $getFeeAssigned['getAllAssigned'] : []);
        $data['getAllAdditionStudentFee']   = ($classID && $studentID ? $getFeeAssigned['getAllAdditionFee'] : []); 
        $data['getAllAssignedCoreFees']     = ($classID ? $getCoreFeeAssigned : []);
        $data['getPreviousOutstandingFees'] = 0;
        
        $SUMCoreFeeAmount       = 0;
        $SUMFeeAmount           = 0;
        $SUMAdditionalFeeAmount = 0;
        $totalPaid              = 0;
        $totalPreviousOutstanding = 0;
        $totalPreviousDebt1st   = 0;
        $totalPreviousDebt2nd   = 0;
        $totalPreviousDebt3rd   = 0;
        
        //Get Total Previous Debt (Previous Session)
        $previousAmountPaid1st = StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->where('termID', 1)->orderBy('paymentfeesID', 'Desc')->sum('amount_paid');
        $previousAmountPaid2nd = StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->where('termID', 2)->orderBy('paymentfeesID', 'Desc')->sum('amount_paid');
        $previousAmountPaid3rd = StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->where('termID', 3)->orderBy('paymentfeesID', 'Desc')->sum('amount_paid');
        
        $previousTotalAmountDue1st = StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->where('termID', 1)->orderBy('paymentfeesID', 'Desc')->value('total_amount_due');
        $previousTotalAmountDue2nd = StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->where('termID', 2)->orderBy('paymentfeesID', 'Desc')->value('total_amount_due');
        $previousTotalAmountDue3rd = StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->where('termID', 3)->orderBy('paymentfeesID', 'Desc')->value('total_amount_due');
        
        $totalPreviousDebt1st   = $previousTotalAmountDue1st - $previousAmountPaid1st;
        $totalPreviousDebt2nd   = $previousTotalAmountDue2nd - $previousAmountPaid2nd;
        $totalPreviousDebt3rd   = $previousTotalAmountDue3rd - $previousAmountPaid3rd;
        
        $totalPreviousOutstanding = $totalPreviousDebt1st + $totalPreviousDebt2nd + $totalPreviousDebt3rd;
        /////////////end previous debt////////////////////
        
        //Core fee Amount
        if($termID == 4)
        {
            if($studentID and $data['getAllAssignedCoreFees']){
                foreach($data['getAllAssignedCoreFees'] as $key=>$newCore){
                    $getCoreAmonunt = StudentFeeSetup::where('feeID', $newCore->feessetupID)->where('studentID', $studentID)->first();
                    $SUMCoreFeeAmount += ($getCoreAmonunt ? $getCoreAmonunt->amount * 3 : $newCore->amount * 3);
                }
            }
        }else{
            if($studentID and $data['getAllAssignedCoreFees']){
                foreach($data['getAllAssignedCoreFees'] as $key=>$newCore){
                    $getCoreAmonunt = StudentFeeSetup::where('feeID', $newCore->feessetupID)->where('studentID', $studentID)->first();
                    $SUMCoreFeeAmount += ($getCoreAmonunt ? $getCoreAmonunt->amount : $newCore->amount);
                }
            }
        }
        
        //periodic fee amount
        if($studentID and $data['getAllAssignedFees']){
            foreach($data['getAllAssignedFees'] as $keyP=>$newfee){
                $getAmonunt = StudentFeeSetup::where('feeID', $newfee->feessetupID)->where('studentID', $studentID)->first();
                $SUMFeeAmount += ($getAmonunt ? $getAmonunt->amount : $newfee->amount);
            }
        }
        //Additional fee amount
        if($studentID and $data['getAllAdditionStudentFee']){
            foreach($data['getAllAdditionStudentFee'] as $keyMore=>$newMorefee){
                $getAdditionalAmonunt = StudentFeeSetup::where('feeID', $newMorefee->feessetupID)->where('studentID', $studentID)->where('classID', $classID)->first();
                $SUMAdditionalFeeAmount += ($getAdditionalAmonunt ? $getAdditionalAmonunt->amount : $newMorefee->amount);
            }
        }
        //get session or use current session
        $schoolSessionCode =  $this->returnSessionCode($getSessionID); 
        //Get Total Amount Paid so far
        if($termID >= 4)
        {
            $totalPaid = StudentPaymentFee::where('session_code', $schoolSessionCode)->where('studentID', $studentID)->where('classID', $classID)->sum('amount_paid');
            $data['paymentHistory'] = StudentPaymentFee::where('session_code', $schoolSessionCode)
                ->leftJoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                ->where('student_payment_fees.studentID', $studentID)
                ->where('student_payment_fees.classID', $classID)
                ->orderBy('student_payment_fees.paymentfeesID', 'Desc')
                ->paginate(50);
        }else{
            $totalPaid = StudentPaymentFee::where('session_code', $schoolSessionCode)->where('termID', $termID)->where('studentID', $studentID)->where('classID', $classID)->sum('amount_paid');
            $data['paymentHistory'] = StudentPaymentFee::where('session_code', $schoolSessionCode)
                ->leftJoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                ->where('student_payment_fees.termID', $termID)
                ->where('student_payment_fees.studentID', $studentID)
                ->where('student_payment_fees.classID', $classID)
                ->orderBy('student_payment_fees.paymentfeesID', 'Desc')
                ->paginate(50);
        }
        
        $data['totalAdditionalFee']                 = $SUMAdditionalFeeAmount;
        $data['totalPeriodicFee']                   = $SUMFeeAmount;
        $data['totalCoreFee']                       = $SUMCoreFeeAmount;
        $data['totalAmountPaidSoFar']               = $totalPaid;
        //if student has not paid any money last session
        if(!StudentPaymentFee::whereRaw("REPLACE(`session_code`, '/', '') < ? ", str_replace('/', '', $this->returnSessionCode($getSessionID)))->where('studentID', $studentID)->first())
        {
            $data['getPreviousOutstandingFees']     = 0;
        }else{
            $data['getPreviousOutstandingFees']     = $totalPreviousOutstanding;
        }
        $data['totalAmountDueToBePaidPerStudent']   = ($SUMCoreFeeAmount) + ($SUMFeeAmount) + ($SUMAdditionalFeeAmount) + ($data['getPreviousOutstandingFees']); 
        $data['totalBalanceLeft']                   = ($data['totalAmountDueToBePaidPerStudent'] - $data['totalAmountPaidSoFar']);
        //
        return $data;
    }


    //GET ALL PAYMENT REPORT BY CLASS/SESSION AND TERM
    public function getAllPaymentReport($classID, $termID, $getSessionID, $reportType) 
    {   
        $totalAmountToBePaid        = array();
        $totalAmountPaid            = array();
        $outstanding                = array(); 
        $paymentType                = null;
        //Total class fee amount to pay
        $totalClassAmountToPaid = $this->totalCoreAndPeriodicFeeTobePaidByClassOnly($classID, $termID);
        $sessionCode = $this->returnSessionCode($getSessionID);
        //$sessionCode = (PublicSession::find($getSessionID) ? PublicSession::find($getSessionID)->session_name : ($getSessionID <> null and $this->getSession() ? $this->getSession()->session_code : $getSessionID) ); 
        if($classID and $sessionCode and $reportType)
        { 
            $listOfStudentArray = $this->debtorListsANDPaidListANDNotPaidStudentList($classID, $termID, $sessionCode);
            if($reportType == 2) //COMPLETE PAYMENT
            {
                $paymentType = "LIST OF ALL COMPLETED PAYMENTS";
                
                if($termID <> 4)
                {
                    $getPaymentQuery = StudentPaymentFee::where('student_payment_fees.active', 1)
                    ->leftjoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                    ->whereIn('student_payment_fees.studentID', $listOfStudentArray['studentCompletedPayment'])
                    ->where('student_payment_fees.termID', $termID)
                    ->orderBy('student_payment_fees.studentName', 'Asc')
                    ->orderBy('student_payment_fees.termID', 'Asc')
                    ->orderBy('student_payment_fees.payment_date', 'Desc')
                    ->groupBy('student_payment_fees.termID')
                    ->groupBy('student_payment_fees.studentID')
                    ->paginate(10);
                }else{
                    $getPaymentQuery = StudentPaymentFee::where('student_payment_fees.active', 1)
                    ->leftjoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                    ->whereIn('student_payment_fees.studentID', $listOfStudentArray['studentCompletedPayment'])
                    ->orderBy('student_payment_fees.studentName', 'Asc')
                    ->orderBy('student_payment_fees.termID', 'Asc')
                    ->orderBy('student_payment_fees.payment_date', 'Desc')
                    ->groupBy('student_payment_fees.studentID')
                    ->paginate(10);
                }
                $totalClassAmountToPaid['totalAmounttoBePaidByClass'] = 0;
            }else  if($reportType == 3)
            { //   OUTSTANDING - PART PAYMENT
                $paymentType = "LIST OF ALL OUTSTANDING PAYMENTS";
                if($termID <> 4)
                {
                    $getPaymentQuery = StudentPaymentFee::where('student_payment_fees.active', 1)
                    ->leftjoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                    ->whereIn('student_payment_fees.studentID', $listOfStudentArray['studentPaidPartPayment'])
                    ->where('student_payment_fees.termID', $termID)
                    ->orderBy('student_payment_fees.studentName', 'Asc')
                    ->orderBy('student_payment_fees.termID', 'Asc')
                    ->orderBy('student_payment_fees.payment_date', 'Desc')
                    ->groupBy('student_payment_fees.termID')
                    ->groupBy('student_payment_fees.studentID')
                    ->paginate(50);
                }else{
                    $getPaymentQuery = StudentPaymentFee::where('student_payment_fees.active', 1)
                    ->leftjoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                    ->whereIn('student_payment_fees.studentID', $listOfStudentArray['studentPaidPartPayment'])
                    ->orderBy('student_payment_fees.studentName', 'Asc')
                    ->orderBy('student_payment_fees.termID', 'Asc')
                    ->orderBy('student_payment_fees.payment_date', 'Desc')
                    ->groupBy('student_payment_fees.studentID')
                    ->paginate(50);
                }
                $totalClassAmountToPaid['totalAmounttoBePaidByClass'] = 0;
            }else  if($reportType == 4){ //NOT PAID AT ALL
                $paymentType = "LIST OF ALL DEBTORS";
                $getPaymentQuery = Student::whereIn('student.studentID', $listOfStudentArray['studentNotPaidAtAll'])
                    ->leftjoin('student_class', 'student_class.classID', '=', 'student.student_class')
                    ->leftjoin('student_payment_fees', 'student_payment_fees.studentID', '=', 'student.studentID')
                    ->orderBy('student.student_lastname', 'Asc')
                    ->orderBy('student_payment_fees.termID', 'Asc')
                    ->orderBy('student_payment_fees.payment_date', 'Desc')
                    ->groupBy('student.studentID')
                    ->paginate(50);
            }else{ //All -PAID AND UNPAID
                $paymentType = "LIST OF ALL PAYMENTS";
                if($termID <> 4)
                {
                    $getPaymentQuery = Student::where('student.student_class', $classID)
                        ->leftjoin('student_payment_fees', 'student_payment_fees.studentID', '=', 'student.studentID')
                        ->leftjoin('student_class', 'student_class.classID', '=', 'student.student_class')
                        ->leftjoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                        //->where('student_payment_fees.termID', $termID)
                        //->where('student_payment_fees.session_code', $sessionCode)
                        ->orderBy('student.student_lastname', 'Asc')
                        ->groupBy('student.studentID')
                        ->paginate(50);
                }else{
                    $getPaymentQuery = Student::where('student.student_class', $classID)
                        ->leftjoin('student_payment_fees', 'student_payment_fees.studentID', '=', 'student.studentID')
                        ->leftjoin('student_class', 'student_class.classID', '=', 'student.student_class')
                        ->leftjoin('term', 'term.termID', '=', 'student_payment_fees.termID')
                        //->where('student_payment_fees.session_code', $sessionCode)
                        ->orderBy('student.student_lastname', 'Asc')
                        ->groupBy('student.studentID')
                        ->paginate(50);
                }
               
            }
            foreach($getPaymentQuery as $key=>$payment)
            {     
                $getReport = $this->totalStudentFeeToBePaidANDPaidANDBalance($payment->studentID, $payment->classID, $termID, $sessionCode);
                $totalAmountToBePaid[$key.$payment->studentID]  = $getReport['totalAmountDueToBePaidPerStudent'];
                $outstanding[$key.$payment->studentID]          = $getReport['totalBalanceLeft'];
                $totalAmountPaid[$key.$payment->studentID]      = $getReport['totalAmountPaidSoFar'];
            }

            //Total Fees to be paid by a class
            $data['totalAmounttoBePaidByClass'] = $totalClassAmountToPaid['totalAmounttoBePaidByClass'];
            $data['getPaymentQuery']            = $getPaymentQuery;
            $data['totalAmountToBePaidStudent'] = $totalAmountToBePaid;
            $data['totalAmountPaidStudent']     = $totalAmountPaid;
            $data['outstandingStudent']         = $outstanding;
            $data['className']                  = (StudentClass::find($classID) ? StudentClass::find($classID)->class_name : '');
            $data['termName']                   = (Term::find($termID) ? Term::find($termID)->term_name : 'Session');
            $data['sessionName']                = $this->returnSessionCode($sessionCode);
            $data['termID']                     = $termID;
            $data['paymentType']                = $paymentType;
        }else{
            //Total Fee to be paid by a class
            $data['totalAmounttoBePaidByClass'] = 0;
            $data['getPaymentQuery']            = [];
            $data['totalAmountToBePaidStudent'] = 0;
            $data['totalAmountPaidStudent']     = 0;
            $data['outstandingStudent']         = 0;
            $data['className']                  = null;
            $data['termName']                   = null;
            $data['sessionName']                = null;
            $data['termID']                     = 0;
            $data['paymentType']                = null;
        }
        //
        return $data;
    }


    //List of Debtors Paid and Not Paid
    public function debtorListsANDPaidListANDNotPaidStudentList($classID, $termID, $sessionCode)
    {
            $data['studentCompletedPayment']    = [];
            $data['studentPaidPartPayment']     = [];
            $data['studentNotPaidAtAll']        = [];
        try{
            if($classID and $sessionCode and $termID)
            {   
                $getAllStudentDetals = Student::where('student.deleted', 0)->where('student.active', 1)
                    ->where('student_class', $classID)
                    ->select('studentID')->get();
                //check all students balances
                $studentCompletedPayment = [];
                $studentPaidPartPayment = [];
                $studentNotPaidAtAll = [];
                foreach($getAllStudentDetals as $key=>$eachStudent)
                {     
                    $getReportCheck = $this->totalStudentFeeToBePaidANDPaidANDBalance($eachStudent->studentID, $classID, $termID, $sessionCode);
                    //Students that have completed their payment
                    if($getReportCheck['totalBalanceLeft'] <= 0 and $getReportCheck['totalAmountPaidSoFar'] > 0)
                    {
                        $studentCompletedPayment[$key]  = $eachStudent->studentID;
                    }
                    //Students that have paid part of their payment
                    if($getReportCheck['totalBalanceLeft'] > 0 and $getReportCheck['totalAmountPaidSoFar'] > 0)
                    {
                        $studentPaidPartPayment[$key]  = $eachStudent->studentID;
                    }
                    //Students that have not paid at all
                    if($getReportCheck['totalBalanceLeft'] > 0 and $getReportCheck['totalAmountPaidSoFar'] <= 0)
                    {
                        $studentNotPaidAtAll[$key]  = $eachStudent->studentID;
                    }
                }
            }
            $data['studentCompletedPayment']    = $studentCompletedPayment;
            $data['studentPaidPartPayment']     = $studentPaidPartPayment;
            $data['studentNotPaidAtAll']        = $studentNotPaidAtAll;
        }catch (\Exception $e) {
            
        }
        return $data;
        
    }
    
    
    //fee setup
    public function getClassFeesSetupEnquiry($getClassID, $getTermID, $getSessionID) 
    {  
        $classID            = $getClassID; 
        $termID             = $getTermID; 

        $newCoreFeeAmount = array();
        $newFeeAmount = array();
        $newAdditionalFeeAmount = array();
        $SUMCoreFeeAmount = 0;
        $SUMFeeAmount = 0;

        $data['studentImagePath']   = $this->studentImagePath();
        $data['classNameValue']     = $classID;
        $data['className']          = (StudentClass::find($classID) ? StudentClass::find($classID)->class_name : '');
        $data['schoolTermValue']    = $termID;
        $data['termName']           = ($termID ? $this->getTermName($termID) : ''); 
        $data['schoolSession']      = $this->returnSessionCode($getSessionID);
        $data['allFeesSetup']       = $this->activeFees();
        
        //
        if($termID == 4)
        {
            $getAllAssigned = Fees::where('class_fees_setup.classID', $classID) 
                ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
                ->Where('fees.core_fee', '<>', 1)
                ->orderBy('fees.fees_name', 'Asc')
                ->get();
        }else{
            $getAllAssigned = Fees::where('class_fees_setup.classID', $classID) 
                ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
                ->Where('fees.fees_occurent_type', $termID)
                ->Where('fees.core_fee', '<>', 1)
                ->orderBy('fees.fees_name', 'Asc')
                ->get();
        }
        
        ($classID ? $data['getAllAssignedFees'] = $getAllAssigned : $data['getAllAssignedFees'] = []);
        ($classID ? $data['getAllAssignedCoreFees'] = $this->getAllAssignedCoreFeesSetup($classID) : $data['getAllAssignedCoreFees'] = []);
        
        //core fee amount
        if($data['getAllAssignedCoreFees']){
            foreach($data['getAllAssignedCoreFees'] as $key=>$newCore){
                $newCoreFeeAmount[$key.$newCore->feessetupID] = ($newCore->amount);
                $SUMCoreFeeAmount += ($newCore->amount);
            }
        }
        //periodic fee amount
        if($data['getAllAssignedFees']){
            foreach($data['getAllAssignedFees'] as $keyP=>$newfee){
                $newFeeAmount[$keyP.$newfee->feessetupID] = ($newfee->amount);
                $SUMFeeAmount += ($newfee->amount);
            }
        }
        
        $data['classCoreFees']                  = $newCoreFeeAmount;
        $data['classPeriodicFee']               = $newFeeAmount;
        $data['totalCoreFeeAmount']             = $SUMCoreFeeAmount;
        $data['totalPeriodicFeeAmount']         = $SUMFeeAmount;
        //
        return $data;
    }



     //GET ALL TOTAL AMOUNT AND FEE ASSIGNED TO A CLASS
     public function totalCoreAndPeriodicFeeTobePaidByClassOnly($classID, $termID)
     { 
        $SUMCoreFeeAmount = 0;
        $SUMFeeAmount = 0;
        try{
            if($termID == 4)
            {
                $getFeeAssigned['getAllAssigned'] = Fees::where('class_fees_setup.classID', $classID) 
                    ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
                    ->Where('fees.core_fee', '<>', 1)
                    ->orderBy('fees_name', 'Asc')
                    ->get();
            }else{
                $getFeeAssigned['getAllAssigned'] = Fees::where('class_fees_setup.classID', $classID) 
                    ->join('class_fees_setup', 'class_fees_setup.feeID', '=', 'fees.feessetupID')
                    ->Where('fees.fees_occurent_type', $termID)
                    ->orderBy('fees_name', 'Asc')
                    ->get();
            }
            $getCoreFeeAssigned = $this->getAllAssignedCoreFeesSetup($classID);

            $getAllAssignedFees         = ($classID ? $getFeeAssigned['getAllAssigned'] : []);
            $getAllAssignedCoreFees     = ($classID ? $getCoreFeeAssigned : []);

            //core fee amount
            if($getAllAssignedCoreFees){
                foreach($getAllAssignedCoreFees as $key=>$newCore){
                    $SUMCoreFeeAmount += ($newCore->amount);
                }
            }
            //periodic fee amount
            if($getAllAssignedFees){
                foreach($getAllAssignedFees as $keyP=>$newfee){
                    $SUMFeeAmount += ($newfee->amount);
                }
            }
        }catch (\Exception $e) {

        }
        $data['totalAmounttoBePaidByClass']      = ($SUMCoreFeeAmount + $SUMFeeAmount);
        return $data;
     }


    //Search Student From Class
    public function searchActiveStudentFromClass(Request $request)
    {   
         Session::forget('classIDFee');
         Session::forget('studentIDFee');
         Session::forget('schoolTerm');

         $this->validate($request, [
            //'schoolSession' => 'required|string|max:255', 
            'schoolTerm'    => 'required|alpha_num|max:255', 
            'className'     => 'required|alpha_num|max:255', 
        ]);
        //Fee payment
        if($request['paymentReport'] == 0 or $request['paymentReport'] == null)
        {
            $this->validate($request, [
                'studentName'   => 'required|alpha_num|max:255',
            ]);
        } 
        //Payment Report
        if($request['paymentReport'] == 1)
        {
            $this->validate($request, [
                'reportType'   => 'required|alpha_num|max:255',
            ]);
        } 
         Session::put('classIDFee', $request['className']);
         Session::put('studentIDFee', $request['studentName']);
         Session::put('schoolTerm', $request['schoolTerm']);
         Session::put('publicSessionID', $request['schoolSession']);
         Session::put('reportType', $request['reportType']);
         
         return redirect()->back();
    }//
    
    //Class Fees Setup History
    public function insertUpdateClassFeesSetupHistory($feeID, $classID, $currentSessionCode)
	{
        //Update History
        if(ClassFeesSetupHistory::where('feeID', $feeID)->where('classID', $classID)->where('session_code', $currentSessionCode)->first())
        {
            //Update : Class Fees Setup History
            $sessionCode = ($this->getSession() ? $this->getSession()->session_code : null);
            $feesSetHistory                 = ClassFeesSetupHistory::where('feeID', $feeID)->where('classID', $classID)->where('session_code', $currentSessionCode)->first();
            $feesSetHistory->feeID          = $feeID;
            $feesSetHistory->fees_name      = (Fees::find($feeID) ? Fees::find($feeID)->fees_name : null);
            $feesSetHistory->classID        = $classID;
            $feesSetHistory->termID         = (Fees::find($feeID) ? Fees::find($feeID)->fees_occurent_type : 0);
            $feesSetHistory->session_code   = $sessionCode;
            $feesSetHistory->fee_amount     = (Fees::find($feeID) ? Fees::find($feeID)->amount : 0);
            $feesSetHistory->created_at     = date('Y-m-d');
            $feesSetHistory->updated_at     = date('Y-m-d');
            $feesSetHistory->save();
        }else{
            //insert : Class Fees Setup History
            $feesSetHistory                 = new ClassFeesSetupHistory;
            $feesSetHistory->feeID          = $feeID;
            $feesSetHistory->fees_name      = (Fees::find($feeID) ? Fees::find($feeID)->fees_name : null);
            $feesSetHistory->classID        = $classID;
            $feesSetHistory->termID         = (Fees::find($feeID) ? Fees::find($feeID)->fees_occurent_type : 0);
            $feesSetHistory->session_code   = $currentSessionCode;
            $feesSetHistory->fee_amount     = (Fees::find($feeID) ? Fees::find($feeID)->amount : 0);
            $feesSetHistory->created_at     = date('Y-m-d');
            $feesSetHistory->updated_at     = date('Y-m-d');
            $feesSetHistory->save();
        }
        
        return;
    }//
    
    //Class Fees Setup History
    public function deleteClassFeesSetupHistory($feeID, $classID, $currentSessionCode)
	{
        if(ClassFeesSetupHistory::where('feeID', $feeID)->where('classID', $classID)->where('session_code', $currentSessionCode)->first())
        {
            ClassFeesSetupHistory::where('feeID', $feeID)->where('classID', $classID)->where('session_code', $currentSessionCode)->delete();
        }
        
        return;
	}
    
    

}//End class
