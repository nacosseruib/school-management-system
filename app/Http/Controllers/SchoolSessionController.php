<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\SchoolSession;
use Auth;
use Schema;
use Session;
use DB;

class SchoolSessionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createSchoolSession()
    {
        $data['allTerm']        = $this->getTerm();
        $data['currentSession'] = $this->getSession();
        $data['getSessionHistory'] = $this->getSchoolSessionHistory();

        return view('setup.schoolSession', $data);
    }
    
    //
    public function postSchoolSession(Request $request)
    {   
        $this->validate($request, [
            'schoolSession'         => 'required|string|max:255', 
            'sessionDescription'    => 'required|string|max:255', 
            'termName'              => 'required|alpha_num|max:255',
            'allowResultComputation' => 'required|numeric|max:2',
        ]);
        $getSessionCode         =  trim($request->schoolSession);
        $getSessionName         = trim($request->sessionDescription);
        $getTermID              = trim($request->termName);
        $allowResultComputation = trim($request->allowResultComputation);
        $checkIfExist = SchoolSession::where('session_code', $getSessionCode)->where('current_termID', $getTermID)->first();
        if($checkIfExist){
            SchoolSession::where('active', 0)
                ->orwhere('active', 1)
                ->update(array(
                'active' => 0,
                'allow_result_computation' => 0,
            ));
            SchoolSession::where('session_code', $getSessionCode)
                ->where('current_termID', $getTermID)
                ->update(array(
                'session_name' => $getSessionName,
                'allow_result_computation' => $allowResultComputation,
                'active' => 1,
            ));
            return back()->with('message', 'NOTE: The selected school session has been set as the current school session because it has been added before.');
        }else{
            SchoolSession::where('active', 0)
                ->orwhere('active', 1)
                ->update(array(
                'active' => 0,
                'allow_result_computation' => 0,
            ));
            $schoolSession = new SchoolSession;
            $schoolSession->session_code     = $getSessionCode;
            $schoolSession->session_name     = $getSessionName;
            $schoolSession->current_termID   = $getTermID;
            $schoolSession->active           = 1;
            $schoolSession->allow_result_computation = $allowResultComputation;
            $schoolSession->created_at       = date('Y-m-d H:i:s');
            if($schoolSession->save()){
                //set school session
                Session::forget('getSchoolSession');
                Session::put('getSchoolSession', $this->getSession());
                
                return redirect()->route('createSchoolSession')->with('message', 'Your school session has been added and set as the current school session');
            }else{
                return redirect()->route('createSchoolSession')->with('error', 'Sorry, we are unable to set your school session properly! Please, review for proper functioning.');
            }
        }
        return redirect()->route('createSchoolSession')->with('error', 'Sorry, we are unable to set your school session! Please, try again.');

    }//

    



}//end class
