<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PublicSession;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\ResultPin;
use App\User;
use App\Models\Mark;
use App\Models\Term;
use Auth;
use Session;
use DB;


class WelcomeController extends ViewResultFunctionController
{
    
    public function index()
    {  
        //
        if(Auth::check()){
            return redirect()->route('home');
        }

        //
        if(!empty(Session::get('getResultData')))
        {
            return redirect()->route('viewStudentReport');
        }

        $data['getPublishedSession'] = PublicSession::where('public_session.active', 1)
               ->orderBy('public_session.session_name', 'Desc')
               ->get();
        $data['allTerm']            = Term::where('active', 1)->get();
        $data['totalStudent']       = Student::where('active', 1)->where('deleted', 0)->count();
        $data['totalTeacher']       = User::where('suspend', 0)->where('deleted', 0)->where('id', '<>', 1)->count();
        $data['totalResultViewer']  = ResultPin::sum('user_no_of_time_use');
        $data['totalResultComputed'] = Mark::where('active', 1)->where('publish', 1)->count();
        $getWebsite                 = SchoolProfile::where('active', 1)->value('website');
        $data['mainWebsiteUrl']     = (($getWebsite) ? 'http://' . preg_replace('#^https?://#', '', $getWebsite) : '#');
        // 
        return view('welcome', $data);
    }

    //
    public function postParentParameters(Request $request)
    {
        $this->validate($request, [
            'studentRegistrationId'     => 'required|string|max:255', 
            'pinCode'                   => 'required|string|max:255', 
            'schoolSession'             => 'required|string|max:255', 
            'schoolTerm'                => 'required|string|max:255',
        ]);
        $studentRegID           = trim($request['studentRegistrationId']);
        $pinCode                = trim(strtoupper($request['pinCode']));
        $publicSessionID        = $request['schoolSession'];
        $termID                 = $request['schoolTerm'];
        
        $publicSession          = PublicSession::where('publicSessionID', $publicSessionID)->first();
        
        if((Student::where('student_regID', $studentRegID)->where('active', 1)->where('deleted', 0)->first()))
        {
            $getStudent = Student::where('student_regID', $studentRegID)->where('active', 1)->where('deleted', 0)->first();
            $studentID      = $getStudent->studentID;
            $classID        = $getStudent->student_class;
            //School Profile
            $profile = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->first();
            (($profile) ? Session::put('getSchoolProfile', $profile) : '');
            //School logo path
            Session::put('path', 'appAssets/schoolLogo/');
            
            //final validation
            if($studentID && $classID && $termID && $publicSessionID && $pinCode)
            {   
                //Check PIN validity
                $pinDetails = ResultPin::where('pin', $pinCode)->where('has_expire', 0)->where('is_pin_active', 1)->first();
                
                
                if($pinDetails && $publicSession)
                {   
                    //check PIN Type
                    if(($pinDetails->pin_type == 'PIN_PER_USER_TERM') and ($termID == $pinDetails->school_termID) and ($pinDetails->school_session == $publicSession->session_name) and ($pinDetails->classID == $classID))
                    {
                        $this->postStudentReportSheetParametersResult($studentID, $classID, $termID, $publicSessionID);
                        //log PIN
                        $this->PINLog($pinCode);
                        //
                        return redirect()->route('viewStudentReport');
                    }elseif(($pinDetails->pin_type == 'PIN_ANY_USER_TERM'))
                    {
                        
                    }elseif(($pinDetails->pin_type == 'PIN_PER_USER_SESSION'))
                    {
                        
                    }elseif(($pinDetails->pin_type == 'PIN_PER_USER_ONE_TIME'))
                    {
                        
                    }elseif(($pinDetails->pin_type == 'PIN_NO_LIMIT_A_YEAR'))
                    {
                        
                    }else{
                        return redirect()->back()->with('error', 'Sorry, check your PIN or Student Reg.No !');
                    }   
                }else{
                    return redirect()->route('guest');
                }
            }else{
                //
                return redirect()->route('guest');
            }
        }else{
            return redirect()->route('guest');
        }
        //
        return redirect()->route('guest');
    }

    //Parent- view report card
    public function viewStudentResult()
    {
        $data = $this->viewStudentReportSheetResult();
        if(empty(Session::get('getResultData')))
        {
            return redirect()->route('guest');
        }
        //
        return view('Parent.reportSheet', $data);
    }

    //Parent Logout
    public function parentLogout()
    {   
        $PIN = Session::get('PIN');
        $pinLog = ResultPin::find(ResultPin::where('pin', $PIN)->value('pinID'));
        if($pinLog)
        {
            $pinLog->pin_token = null;
            $pinLog->save();
        }
        //
        Session::forget('getResultData');
        Session::flush();
        //
        return redirect()->route('guest');
    }

    
    //PIN Log
    public function PINLog($PIN)
    {
        $pinLog = ResultPin::find(ResultPin::where('pin', $PIN)->value('pinID'));
        if($pinLog)
        {
            $pinLog->user_no_of_time_use = (ResultPin::where('pin', $PIN)->value('user_no_of_time_use') + 1);
            //$pinLog->user_pi_address     = Request::ip();
            $pinLog->user_location       = gethostname();
            $pinLog->user_last_login     = date('Y-m-d h:i:sa');
            $pinLog->user_route          = url()->current();
            $pinLog->pin_token           = $this->get_rand_alphanumeric(30);
            $pinLog->save();
        }
        Session::put('PIN', $PIN);
        return;
    }



}//end class
