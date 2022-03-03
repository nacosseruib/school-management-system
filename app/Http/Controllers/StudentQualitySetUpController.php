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
use App\Models\Mark;
use App\Models\Term;
use Auth;
use Schema;
use Session;
use DB;

class StudentQualitySetUpController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createStudentQualitySetup()
    {   
        $classID        = Session::get('classID');
        $studentID      = Session::get('studentID');
        $data['student']    = $this->getStudentQuality($classID, $studentID);
        $data['allClass']   = $this->getClass(); 
        //
        return view('setup.studentExtraCurricularSetUp', $data);
    }
    
    //
    public function updateStudentQualitySetup(Request $request)
    {   
        $this->validate($request, [
            'qualityID'     => 'required|array', 
        ]);
        $qualityID  = $request['qualityID'];
        $excellent  = $request['excellent'];
        $good       = $request['good'];
        $fair       = $request['fair'];
        $poor       = $request['poor'];
        //get all Good array
		foreach ($excellent as $excellent) {
            $arrayExcellent[] = $excellent;
        }
        //get all Good array
		foreach ($good as $good) {
            $arrayGood[] = $good;
        }
        //get all Fair array
		foreach ($fair as $fair) {
            $arrayFair[] = $fair;
        }
        //get all Good array
		foreach ($poor as $poor) {
            $arrayPoor[] = $poor;
        }
        //Start processing...
		foreach ($qualityID as $key=>$eachStudent) {
            DB::table('student_extra')->where('student_extraID', $eachStudent)->update([
                'excellent' => $arrayExcellent[$key],
                'good'      => $arrayGood[$key],
                'fair'      => $arrayFair[$key],
                'poor'      => $arrayPoor[$key]
            ]);
        }
        //
        return redirect()->route('studentQualitySetUp')->with('message', 'Your records were updated successfully');
    }


    //Search Student Quality
    public function searchStudentQualitySetup(Request $request)
    {   
        Session::forget('classID');
        Session::forget('studentID');

        $this->validate($request, [
            'className'     => 'required|string', 
            'studentName'   => 'required|string', 
        ]);
        Session::put('classID', $request['className']);
        Session::put('studentID', $request['studentName']);
        //
        return redirect()->route('studentQualitySetUp');
    }
    

}//end class
