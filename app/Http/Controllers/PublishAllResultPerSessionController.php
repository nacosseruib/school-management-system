<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentClass;
use App\Models\ScoreType;
use App\Models\Mark;
use App\Models\Term;
use App\Models\PublicSession;
use App\Models\StudentSubject;
use App\Models\SchoolProfile;
use App\User;
use Auth;
use Schema;
use Session;
use DB;

class PublishAllResultPerSessionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createPublishResult()
    {
        $data['allClass']       = $this->getClass();
        $data['allSubject']     = $this->getSubject();
        $data['allScoreType']   = $this->getScoreType();
        $data['allTerm']        = $this->getTerm();
        $data['getSession']     = $this->getSession();

        if(empty(Session::get('newFoundStudent')))
        {
            $data['foundStudent'] = $this->getOnlyStudentInClass(Session::get('classIDSet'));
        }else{
            $data['foundStudent'] =  Session::get('newFoundStudent');
        } 
        return view('publishResult.home', $data);
    }
    
    //
    public function savePublishResult(Request $request)
    {   
        $this->validate($request, [
            'className'         => 'required|alpha_num|max:255', 
            'subjectName'       => 'required|alpha_num|max:255', 
            'scoreTypeName'     => 'required|alpha_num|max:255',
            'termName'          => 'required|numeric|max:255',
            'schoolSession'     => 'required|string|max:255',
        ]);
        $getTermID      =  trim($request->termName);
        $termCode       = Term::where('termID', $getTermID)->value('term_code');
        $termName       = Term::where('termID', $getTermID)->value('term_name');
        $getSession     = trim($request->schoolSession);
        $classID        = trim($request->className);
        $subjectID      = trim($request->subjectName);
        $scoreTypeID    = trim($request->scoreTypeName);
        $success        = 0;
        if($getTermID >= 4)
        {
            return redirect()->back()->with('error', 'Sorry, the system cannot compute for 1st, 2nd & 3rd Term at the same time! Please select each term you want to compute for. Thanks.');
        }
        //
        if(($classID == "All") && ($subjectID == "All") && ($scoreTypeID == "All"))
        {  
            $keyOld = 0; $keyNew = 0; $totalSubjectCompute = 0; $successNoError = 0;
            //get All Score Type
            $allScoreType =  ScoreType::where('active', 1)->get();
            foreach($allScoreType as $listScoreType)
            { 
                $scoreTypeID = $listScoreType->scoretypeID;
                //get All classes
                $allClass =  StudentClass::where('active', 1)->get();
                foreach($allClass as $listClass)
                {   
                    $classID = $listClass->classID;
                    //get all subject in this class
                    $allSubjectInClass =  StudentSubject::where('classID', $classID)->where('active', 1)->get();
                    foreach($allSubjectInClass as $listSubject)
                    {  
                        $subjectID = $listSubject->subjectID;
                        $getData = $this->startPublishProcess($keyOld, $keyNew, $getSession, $classID, $getTermID, $scoreTypeID, $subjectID, $termCode, $termName);
                        $keyNew = $getData['newPublish'];
                        $keyOld = $getData['oldPublish'];
                        $successNoError = $getData['successNoError'];
                        $totalSubjectCompute ++;
                    } //$listSubject->subjectID
                    $subjectID = 0;
                    $this->publicSession($scoreTypeID, $getSession, $termName, $classID, $subjectID, $getTermID, $totalSubjectCompute);
                }
            }
            if($keyNew or $successNoError){
                return redirect()->route('publishResult')->with("message", "Your results were published successfully. <br /> NOTE: <br />" . ($totalSubjectCompute) ." Subject(s) was found. <br /> ". $keyNew ." Student's result(s) was published. <br />". $keyOld ." Student's result(s) was already published.");
            }else{
                return redirect()->route('publishResult')->with('error', 'Sorry, No mark/score found for the selected class! Please, add score and try again.');
            } 
        }elseif(($classID != "All") && ($subjectID == "All") && ($scoreTypeID == "All"))
        {
            $keyOld = 0; $keyNew = 0; $totalSubjectCompute = 0; $successNoError = 0;
            //get All Score Type
            $allScoreType =  ScoreType::where('active', 1)->get();
            foreach($allScoreType as $listScoreType)
            { 
                $scoreTypeID = $listScoreType->scoretypeID;
                $classID = $classID;
                //get all subject in this class
                $allSubjectInClass =  StudentSubject::where('classID', $classID)->where('active', 1)->get();
                foreach($allSubjectInClass as $listSubject)
                {  
                    $subjectID = $listSubject->subjectID;
                    $getData = $this->startPublishProcess($keyOld, $keyNew, $getSession, $classID, $getTermID, $scoreTypeID, $subjectID, $termCode, $termName);
                    $keyNew = $getData['newPublish'];
                    $keyOld = $getData['oldPublish'];
                    $successNoError = $getData['successNoError'];
                    $totalSubjectCompute ++;
                } //$listSubject->subjectID
                $subjectID = 0;
                $this->publicSession($scoreTypeID, $getSession, $termName, $classID, $subjectID, $getTermID, $totalSubjectCompute);
            }
            if($keyNew or $successNoError){
                return redirect()->route('publishResult')->with("message", "Your results were published successfully. <br /> NOTE: <br />" . ($totalSubjectCompute) ." Subject(s) was found. <br /> ". $keyNew ." Student's result(s) was published. <br />". $keyOld ." Student's result(s) was already published.");
            }else{
                return redirect()->route('publishResult')->with('error', 'Sorry, No mark/score found for the selected class! Please, add score and try again.');
            } 

        }elseif(($subjectID == "All") && ($classID != "All") && ($scoreTypeID != "All"))
        {
            //get all subject in this class
            $allSubjectInClass =  StudentSubject::where('classID', $classID)->where('active', 1)->get();
            $keyOld = 0; $keyNew = 0; $totalSubjectCompute = 0; $successNoError = 0;
            foreach($allSubjectInClass as $listSubject)
            {  
                $getData = $this->startPublishProcess($keyOld, $keyNew, $getSession, $classID, $getTermID, $scoreTypeID, $listSubject->subjectID, $termCode, $termName);
                $keyNew = $getData['newPublish'];
                $keyOld = $getData['oldPublish'];
                $successNoError = $getData['successNoError'];
                $totalSubjectCompute ++;
            } 
            $subjectID = 0;
            $this->publicSession($scoreTypeID, $getSession, $termName, $classID, $subjectID, $getTermID, $totalSubjectCompute);
            if($keyNew or $successNoError){
                return redirect()->route('publishResult')->with("message", "Your results were published successfully. <br /> NOTE: <br />" . ($totalSubjectCompute) ." Subject(s) was found. <br /> ". $keyNew ." Student's result(s) was published. <br />". $keyOld ." Student's result(s) was already published.");
            }else{
                return redirect()->route('publishResult')->with('error', 'Sorry, No mark/score found for the selected class! Please, add score and try again.');
            } 

        }elseif($subjectID != "All" and StudentSubject::find($subjectID))
        {   
            $keyOld = 0; $keyNew = 0; $totalSubjectCompute = 1;
            $getData = $this->startPublishProcess($keyOld, $keyNew, $getSession, $classID, $getTermID, $scoreTypeID, $subjectID, $termCode, $termName);
            $this->publicSession($scoreTypeID, $getSession, $termName, $classID, $subjectID, $getTermID, $totalSubjectCompute);
            if($getData['newPublish'] or $getData['successNoError']){
                return redirect()->route('publishResult')->with("message", "Your results were published successfully. <br /> NOTE: <br />" . $totalSubjectCompute ." Subject(s) was found. <br /> ". $getData['newPublish'] ." Student's result(s) was published. <br />". $getData['oldPublish'] ." Student's result(s) was already published.");
            }else{
                return redirect()->route('publishResult')->with('error', 'Sorry, No mark/score found for the selected class! Please, add score and try again.');
            } 

        }else{
            return redirect()->route('publishResult')->with('error', 'Sorry, we cannot publish your result now! Review your parameters and try again.');
        }
        return redirect()->route('publishResult')->with('error', 'Sorry, we cannot publish your result now. It seems some internal errors occured. Try again later');
        
    }//end function


    //this function will be called - Start Computation Module
    public function startPublishProcess($keyOld, $keyNew, $getSession, $classID, $getTermID, $scoreTypeID, $subjectID, $termCode, $termName)
    {
        $getAllMark = Mark::where('session_code', $getSession)->where('classID', $classID)->where('termID', $getTermID)
                ->where('scoretypeID', $scoreTypeID)->where('subjectID', $subjectID)->get(); 
        //
        $successNoError = 0;
        if($getAllMark)
        {
            $oldPublish = $keyOld;
            $newPublish = $keyNew;
            foreach($getAllMark as $listAll)
            {   
                if(SchoolProfile::where('active', 1)->value('update_pulish_result') > 0)
                {
                    $success = Mark::where('session_code', $getSession)
                        ->where('classID', $classID)
                        ->where('termID', $getTermID)
                        ->where('scoretypeID', $scoreTypeID)
                        ->where('subjectID', $subjectID)
                        ->update(array(
                            'scoretype_name'    => ScoreType::where('scoretypeID', $listAll->scoretypeID)->value('score_type_code'),
                            'term_name'         => $termCode,
                            'publish'           => 1,
                            'computed_by'       => User::where('id', $listAll->computed_by_ID)->value('name'),
                            'publish_date_time' => date('Y-m-d H:i:s-A'),
                        )); 
                    $numberOfPublished = 0;
                }else{
                    $success = Mark::where('publish', 0)
                        ->where('session_code', $getSession)
                        ->where('classID', $classID)
                        ->where('termID', $getTermID)
                        ->where('scoretypeID', $scoreTypeID)
                        ->where('subjectID', $subjectID)
                        ->update(array(
                            'scoretype_name'    => ScoreType::where('scoretypeID', $listAll->scoretypeID)->value('score_type_code'),
                            'term_name'         => $termCode,
                            'publish'           => 1,
                            'computed_by'       => User::where('id', $listAll->computed_by_ID)->value('name'),
                            'publish_date_time' => date('Y-m-d H:i:s-A'),
                        )); 
                    $numberOfPublished = $listAll->publish;
                }
                //
                if($numberOfPublished == 1){
                    $oldPublish ++;
                }else{
                    $newPublish ++;
                }
            }//end foreach
            $successNoError = 1;
        }else{
            return redirect()->route('publishResult')->with('error', 'Sorry, no record found for the selected class/subject.');
        }
        $data['oldPublish'] = $oldPublish;
        $data['newPublish'] = $newPublish;
        $data['successNoError'] = $successNoError;
        return $data;
        
    }//endfunction

    
    //this function will be called- create public Session
    public function publicSession($scoreTypeID, $getSession, $termName, $classID, $subjectID, $getTermID, $totalSubjectCompute)
    {   
        $getAllMark = Mark::where('session_code', $getSession)
            ->where('classID', $classID)
            ->where('termID', $getTermID)
            ->where('scoretypeID', $scoreTypeID)
            ->first();
        if($getAllMark)
        {
            $scoreType = ScoreType::where('scoretypeID', $scoreTypeID)->value('score_type_code');
            $getClassName = StudentClass::where('classID', $classID)->value('class_name');
            if(!PublicSession::where('session_name', $getSession)->where('school_termID', $getTermID)->first())
            {
                //Add new FULL TERM
                $publicSession = new PublicSession;
                $publicSession->session_name    = $getSession;
                $publicSession->description     = ("Results were published for Full-Term for ". $totalSubjectCompute .' subject(s), ' .$termName .', '. $getSession .' and '.$scoreType.' by '. Auth::User()->name);
                $publicSession->class_name      = $getClassName;
                $publicSession->school_term     = $termName;
                $publicSession->school_termID   = $getTermID;
                $publicSession->mid_full_term   = $getTermID;
                $publicSession->score_type      = $scoreType;
                $publicSession->userID          = Auth::User()->id;
                $publicSession->created_at      = date('Y-m-d H:i:s-a');
                $success                        = $publicSession->save();
                //Add new MID-TERM
                if($success or $getTermID <= 3){
                    $publicSession = new PublicSession;
                    $publicSession->session_name    = $getSession;
                    $publicSession->description     = ("Results were published for Mid-Term for ". $totalSubjectCompute .' subject(s), ' .$termName .', '. $getSession .' and '.$scoreType.' by '. Auth::User()->name);
                    $publicSession->class_name      = $getClassName;
                    $publicSession->school_term     = $termName .'(Mid-Term)'; 
                    $publicSession->school_termID   = $getTermID;
                    $publicSession->mid_full_term   = $getTermID.$getTermID;
                    $publicSession->score_type      = $scoreType;
                    $publicSession->userID          = Auth::User()->id;
                    $publicSession->created_at      = date('Y-m-d H:i:s-a');
                    $success                        = $publicSession->save();
                }
            }else{
                //update - 
                if(SchoolProfile::where('active', 1)->value('update_pulish_result') > 0)
                { 
                    $success = PublicSession::where('session_name', $getSession)->where('school_termID', $getTermID)->update(array(
                        'session_name'      => $getSession,
                        'description'       => ("Results were published for Full-Term for ". $totalSubjectCompute .' subject(s), ' .$termName .', '. $getSession .' and '.$scoreType.' by '. Auth::User()->name),
                        'class_name'        => $getClassName,
                        'school_term'       => $termName,
                        'school_termID'     => $getTermID,
                        'mid_full_term'     => $getTermID,
                        'score_type'        => $scoreType,
                        'userID'            => Auth::User()->id,
                        'updated_at'        => date('Y-m-d H:i:s-a')
                    ));
                    $success = PublicSession::where('session_name', $getSession)->where('school_termID', $getTermID.$getTermID)->update(array(
                        'session_name'      => $getSession,
                        'description'       => ("Results were published for Mid-Term for ". $totalSubjectCompute .' subject(s), ' .$termName .', '. $getSession .' and '.$scoreType.' by '. Auth::User()->name),
                        'class_name'        => $getClassName,
                        'school_term'       => $termName .'(Mid-Term)',
                        'school_termID'     => $getTermID,
                        'mid_full_term'     => $getTermID.$getTermID,
                        'score_type'        => $scoreType,
                        'userID'            => Auth::User()->id,
                        'updated_at'        => date('Y-m-d H:i:s-a')
                    )); 
                }else{
                    $success = 0;
                }
            }
            return $success;
        }else{
            return 0;

        }
        
    }//end function



}//end class
