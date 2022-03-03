<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentAttendance;
use App\Models\SchoolSession;
use App\Models\SchoolProfile;
use App\Models\Term;
use Auth;
use Schema;
use Session;
use DB;

class StudentAttendanceController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createStudentAttendance()
    {   
        $termID            = Session::get('term');
        $session          = Session::get('session');
        $data['session']    = SchoolSession::get();
        $data['term']       = $this->getTerm();
        $data['studentAttandance']    = $this->getStudentAttendance($termID, $session);
        $data['allClass']   = $this->getClass(); 
        $data['daysSchoolOpens'] = SchoolProfile::where('active', 1)->value('day_school_open');
        //
        return view('setup.studentAttendance', $data);
    }
    
    //
    public function updateStudentAttendanceSetup(Request $request)
    {   
        $this->validate($request, [
            'className'             => 'required|numeric', 
            'studentName'           => 'required|numeric', 
            'term'                  => 'required|numeric', 
            'session'               => 'required|string',
            'totalDaysPresent'      => 'required|numeric', 
            'totalDaysAbsent'       => 'required|numeric',  
        ]);
        $termID             = $request['term'];
        $sessionCode        = $request['session'];
        $studentID          = $request['studentName'];
        $present            = $request['totalDaysPresent'];
        $absent             = $request['totalDaysAbsent'];
        $classID            = $request['className'];
        $otherComment       = $request['otherComment'];
        $daysSchoolOpens    = SchoolProfile::where('active', 1)->value('day_school_open');
        $success = 0;
        //Start processing...
       
        if(StudentAttendance::where('studentID', $studentID)->where('classID', $classID)->where('termID', $termID)->where('session_code', $sessionCode)->first() and ($studentID <> 0 or $studentID <> null) and ($classID <> null))
        {
            //Update record
            $studentAttendance = StudentAttendance::where('studentID', $studentID)->where('classID', $classID)->where('termID', $termID)->where('session_code', $sessionCode)->first();
            $studentAttendance->studentID           = $studentID;
            $studentAttendance->classID             = $classID;
            $studentAttendance->termID              = $termID;
            $studentAttendance->session_code        = $sessionCode;
            $studentAttendance->total_school_open   = $daysSchoolOpens;
            $studentAttendance->total_present       = $present;
            $studentAttendance->total_absent        = $absent;
            $studentAttendance->comment             = $otherComment;
            $studentAttendance->updated_at          = date('Y-m-d H:i:s-a');
            $success = $studentAttendance->save();
        }else {
            if(($studentID <> 0 or $studentID <> null) and ($classID <> null) and ($termID <> null))
            {
                //Insert record
                $studentAttendance = New StudentAttendance;
                $studentAttendance->studentID           = $studentID;
                $studentAttendance->classID             = $classID;
                $studentAttendance->termID              = $termID;
                $studentAttendance->session_code        = $sessionCode;
                $studentAttendance->total_school_open   = $daysSchoolOpens;
                $studentAttendance->total_present       = $present;
                $studentAttendance->total_absent        = $absent;
                $studentAttendance->comment             = $otherComment;
                $studentAttendance->updated_at          = date('Y-m-d H:i:s-a');
                $studentAttendance->created_at          = date('Y-m-d H:i:s-a');
                $success = $studentAttendance->save();
            }else{
                $success = 0;
            }//if
        }//if
        //
        if($success){
            return redirect()->route('studentAttandance')->with('message', 'Your attendance records were updated successfully');
        }else{
            return redirect()->route('studentAttandance')->with('error', 'Sorry, we cannot update your record! Please try again.');
        }
    }


    //Search Student Quality
    public function searchStudentAttendance(Request $request)
    {   
        Session::forget('classID');
        Session::forget('studentID');

        $this->validate($request, [
            //'term'     => 'required|string', 
            //'session'   => 'required|string', 
        ]);
        Session::put('term', $request['term']);
        Session::put('session', $request['session']);
        //
        return redirect()->route('studentAttandance');
    }
    

}//end class
