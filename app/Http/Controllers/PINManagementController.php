<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailPin;
use App\Mail\SendSuperAdminEmailWhenPINGenerated;
use Carbon\Carbon;
use App\Models\StudentClass;
use App\Models\Mark;
use App\Models\Term;
use App\Models\PublicSession;
use App\Models\SchoolProfile;
use App\Models\ResultPin;
use App\Models\Student;
use Twilio\Rest\Client; 
use App\Models\SMSLog;
use App\User;
use Auth;
use Schema;
use Session;
use DB;

class PINManagementController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createResultCheckerPIN()
    {   
        Session::forget('schoolSession');
        Session::forget('termID');
        Session::forget('classID');
        Session::forget('termName');
        Session::forget('className');
        //
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
        return view('PINManagement.home', $data);
    }
    
    //
    public function generateResultCheckerPIN(Request $request)
    {   
        
        $this->validate($request, [
            'schoolSession'    => 'required|string|max:200', 
            'schoolTerm'       => 'required|alpha_num|max:200', 
            'pinType'          => 'required|string|max:200',
            'className'        => 'required|string|max:200',
            'sendEmail'        => 'required|numeric|max:200',
        ]);
        $schoolSession          =  trim($request->schoolSession);
        $schoolTermID           =  trim($request->schoolTerm);
        $pinType                =  trim($request->pinType);
        $classID                =  trim($request->className);
        $sendEmail              =  trim($request->sendEmail);
        $success                = 0;
        $newTermName            = Term::find($schoolTermID)->term_name;
        //check session
        if(($this->getSession()->session_code <> $schoolSession) and  ($this->getSession()->current_termID <> $schoolTermID))
        {
            return redirect()->back()->with('error', 'Sorry, session and term are not the same with school current session and term!');
        }
        //
        if($pinType == 'PIN_PER_USER_TERM')
        {
            $newPinType = 'PIN_PER_USER_TERM';
            $newPinExpire = 'End of '. $newTermName .', '. $schoolSession;
        }elseif($pinType == 'PIN_ANY_USER_TERM')
        {
            $newPinType = 'PIN_ANY_USER_TERM';
            $newPinExpire = $schoolSession .'-'.$newTermName;
        }elseif($pinType == 'PIN_PER_USER_SESSION')
        {
            $newPinType = 'PIN_PER_USER_SESSION';
            $newPinExpire = $schoolSession .'-'.$newTermName;
        }elseif($pinType == 'PIN_PER_USER_ONE_TIME')
        {
            $newPinType = 'PIN_PER_USER_ONE_TIME';
            $newPinExpire = $schoolSession .'-'.$newTermName;
        }elseif($pinType == 'PIN_NO_LIMIT_A_YEAR')
        {
            $newPinType = 'PIN_NO_LIMIT_A_YEAR';
            $newPinExpire = $schoolSession .'-'.$newTermName;
        }else{
            return redirect()->back()->with('error', 'Sorry, we cannot generate PIN with unknown PIN type');
        }
        
        $count = 0;
        $totalPinGenerated = 0;
        $totalAlreadyPinGenerated = 0;
        $schoolDetails = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->select('school_full_name', 'school_short_name')->first();
        $schoolName = ($schoolDetails ? $schoolDetails->school_full_name : 'School Eportal');
        $schoolShortName = ($schoolDetails ? $schoolDetails->school_short_name : '');
                        
        if($schoolSession and $schoolTermID and $pinType)
        {
            if($classID == strtoupper('ALL'))
            {
                $getAllStudent = $this->allActiveStudentForResultPIN();
            }else{
                $getAllStudent = $this->allActiveStudentByClassForResultPIN($classID);
            }
            foreach($getAllStudent as $key=>$eachStudent)
            {   
                
                $PIN = $this->get_rand_alphanumeric(6);
                //check PIN exist
                if($schoolSession <> null)
                {
                    ((ResultPin::where('pin', $PIN)->where('school_session', $schoolSession)->first()) ?  $newPIN = $this->get_rand_alphanumeric(6) : $newPIN = $PIN );
                    $newclassID     = $eachStudent->student_class;
                    $newStudentID   = $eachStudent->studentID;
                    
                    //
                    if(!ResultPin::where('studentID', $newStudentID)->where('classID', $newclassID)->where('school_termID', $schoolTermID)->where('school_session', $schoolSession)->first())
                    {
                        $ResultPin = new ResultPin;
                        $ResultPin->pin                 = $newPIN;
                        $ResultPin->studentID           = $newStudentID;
                        $ResultPin->school_session      = $schoolSession;
                        $ResultPin->school_term_name    = $newTermName;
                        $ResultPin->school_termID       = $schoolTermID;
                        $ResultPin->classID             = $newclassID;
                        $ResultPin->pin_type            = $newPinType;
                        $ResultPin->pin_expire          = $newPinExpire;
                        $ResultPin->is_pin_active       = 1;
                        $ResultPin->send_email          = $sendEmail;
                        $ResultPin->generated_userID    = Auth::User()->id;
                        $ResultPin->created_at          = date('Y-m-d H:i:s-a');
                        $ResultPin->updated_at          = date('Y-m-d H:i:s-a');
                        $success                        = $ResultPin->save();
                       
                        //call email/SMS function NOTE: 1-Email, 2-SMS, 3-Email & SMS, 0-Dont Send any
                        //Get Email Data to Send Email
                        $loginUrl = 'https://'. $_SERVER["HTTP_HOST"];
                        $emailData = ([
                            'name'=>$eachStudent->student_lastname .' '.$eachStudent->student_firstname, 
                            'class'=>$eachStudent->class_name, 
                            'studentID'=>$eachStudent->student_regID, 
                            'session'=>$schoolSession, 
                            'term'=>$newTermName, 
                            'pin'=>$newPIN, 
                            'schoolName' => $schoolName,
                            'loginUrl' => $loginUrl,
                        ]);
                        
                        //SMS MESSAGE TO BE SENT
                        $message = $schoolShortName .": " . $eachStudent->student_lastname .' '.$eachStudent->student_firstname . "-PIN: ". $newPIN .", REGID: " . $eachStudent->student_regID . " for " . $schoolSession .", " .$newTermName . " ".$loginUrl;

                        if($sendEmail == 1 and $emailData)
                        {
                            $emailAddress = $eachStudent->parent_email;
                            try{
                                //Send Email Only
                                ($emailAddress ? Mail::to($emailAddress)->send(new SendEmailPin($emailData)) : '');
                            }catch (\Exception $e) {}

                        }elseif($sendEmail == 2)
                        {
                            $phoneNumber = $eachStudent->parent_telephone; 
                            try{
                                //send SMS Only
                                $this->SEND_SMS_WITH_TWILIO_SMS_API($phoneNumber, $message);
                            }catch (\Exception $e) {}
    
                        }elseif($sendEmail == 3 and $emailData)
                        {
                            $emailAddress = $eachStudent->parent_email;
                            $phoneNumber = $eachStudent->parent_telephone;
                            try{
                                //Send Email
                                ($emailAddress ? Mail::to($emailAddress)->send(new SendEmailPin($emailData)) : '');
                            }catch (\Exception $e) {}
                            
                            try{
                                //Send Email And SMS
                                $this->SEND_SMS_WITH_TWILIO_SMS_API($phoneNumber, $message);
                            }catch (\Exception $e) {}

                        }//Email/SMS
                         $totalPinGenerated += 1;
                    }else{
                        $totalAlreadyPinGenerated +=1;
                    } //endif
                }//
                
            }//end foreach
            
            
            /////////////////////////////Admin Email Data/////////////////
            if($totalPinGenerated)
            {
                //Send SMS TO ADMIN
                $adminNumber = "07034320265";
                $adminMessage = 'NOTIFICATION: ' . $schoolShortName . ' has published and generated PIN(s) for ' . $totalPinGenerated .' student(s) for ' . $schoolSession .'-' .$newTermName .' Thanks';
                $this->SEND_SMS_WITH_TWILIO_SMS_API($adminNumber, $adminMessage);
                
                $getPINDetails = ResultPin::where('result_pin.school_term_name', $newTermName)
                    ->leftJoin('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->leftJoin('student_class', 'student_class.classID', '=', 'student.student_class')
                    ->select('student_class.class_name','student.student_regID', 'student.student_firstname', 'student.student_lastname', 'result_pin.school_term_name', 'result_pin.pin', 'result_pin.created_at', 'result_pin.pin_type')
                    ->where('result_pin.school_session', $schoolSession)
                    ->get();
                $allPINGenerated = ([
                    'getStudentPIN'=>$getPINDetails, 
                    'session'=>$schoolSession, 
                    'term'=>$newTermName, 
                    'schoolName' => SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->value('school_full_name'),
                    'loginUrl'=>'https://'. $_SERVER["HTTP_HOST"] 
                ]);
                //Send Admin Email
                try{
                    ($getPINDetails ? Mail::to('admin@schooleportal.com')->send(new SendSuperAdminEmailWhenPINGenerated($allPINGenerated)) : ''); 
                }catch (\Exception $e) {

                }
            }
            /////////////////////////////////////////////////////////////
            
            
            //
            return redirect()->route('createResultPIN')->with('message', 'Your PINs were generated successfully <br /> NOTE: <br /> '. $totalPinGenerated .' PIN(s) were Generated. <br />'. $totalAlreadyPinGenerated .' PIN(s) Already Generated. <br /> PIN Generated for: '. $newTermName .', '. $schoolSession.' session.');
        }else{
            return redirect()->back()->with('error', 'Sorry, we cannot generate your PIN. Check school session, term or pin type!');
        }//end if

    }//end function
    

     //Search PIN
    public function searchResultPIN(Request $request)
    { 
        Session::forget('schoolSession');
        Session::forget('termID');
        Session::forget('classID');
        Session::forget('termName');
        Session::forget('className');
        
        Session::put('schoolSession', $request->schoolSession);
        Session::put('termID', $request->termName);
        Session::put('classID', $request->className);
        Session::put('termName', ((Term::find($request->termName) and $request->termName) ? Term::find($request->termName)->term_name :  ''));
        Session::put('className',  (($request->className <> null and $request->className <> 'All' and StudentClass::find($request->className)) ? StudentClass::find($request->className)->class_name :  'All Classes'));
        //
        return redirect()->route('viewResultPIN');
    }


    //View and manage PIN
    public function viewResultPIN()
    {   
        $data['allClass']       = $this->getClass();
        $data['allTerm']        = $this->getTerm();
        $data['currentSession'] = $this->getSession();
        $data['getSessionHistory'] = $this->getSchoolSessionHistory();
        $schoolSession = Session::get('schoolSession');
        $termID = Session::get('termID');
        $classID = Session::get('classID');

            //Search with Session and term and single class
            if(($this->getSession()) and $schoolSession and $termID and $classID and $classID <> 'All')
            {
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.school_session', $schoolSession)
                    ->where('result_pin.school_termID', $termID)
                    ->where('result_pin.classID', $classID)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(20);

            //Search with Session and term and all classes only
            }elseif(($this->getSession()) and $schoolSession and $termID and $classID == 'All')
            {
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.school_session', $schoolSession)
                    ->where('result_pin.school_termID', $termID)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(20);

            //Search with Session only
            }elseif(($this->getSession()) and $schoolSession and $termID == null and $classID == null)
            {
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.school_session', $schoolSession)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(20);

            //Search with  single class only
            }elseif(($this->getSession()) and $schoolSession == null and $termID == null and $classID <> 'All' and $classID <> null)
            {
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.classID', $classID)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(20);
            
            //Search with all classes and term only
            }elseif(($this->getSession()) and $schoolSession == null and $termID <> null and ($classID == 'All' or $classID == null))
            {
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.school_termID', $termID)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(20);
            
            //Search with single class and term only
            }elseif(($this->getSession()) and $schoolSession == null and $termID <> null and $classID <> 'All')
            {
            $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                ->where('result_pin.classID', $classID)
                ->where('result_pin.school_termID', $termID)
                ->orderBy('result_pin.studentID', 'Asc')
                ->select('*', 'result_pin.created_at as pinCreated')
                ->paginate(20);

            //Search with Session and single class only
            }elseif(($this->getSession()) and $schoolSession <> null and $termID == null and $classID <> null and $classID <> 'All')
            {
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.school_session', $schoolSession)
                    ->where('result_pin.classID', $classID)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(20);

            }else{
                if($this->getSession()){
                $data['allPINCurrentSession'] = ResultPin::where('result_pin.school_session', $this->getSession()->session_code)
                    ->join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->where('result_pin.school_termID', $this->getSession()->current_termID)
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(50);
                }else{
                    $data['allPINCurrentSession'] = ResultPin::
                    join('student', 'student.studentID', '=', 'result_pin.studentID')
                    ->join('student_class', 'student_class.classID', '=', 'result_pin.classID')
                    ->orderBy('result_pin.studentID', 'Asc')
                    ->select('*', 'result_pin.created_at as pinCreated')
                    ->paginate(50);
                }
            }
        //
        return view('PINManagement.viewPIN', $data);
    }

    //Enable or Disable student
    public function enableDisablePIN(Request $request)
    {   
        $pinID = $request->pinID; 
        $operation = $request->operation;
        $message = "Not Successful! Sorry, we are having issue while updating this PIN. Try againg later.";
        if(ResultPin::find($pinID))
        {
            $pin = ResultPin::find($pinID);
            $pin->is_pin_active = $operation;
            $pin->updated_at    = date('Y-m-d');
            if($pin->save()){
                $message = (($operation == 1) ? "You PIN has been enabled successfully." : "You PIN has been disabled successfully.");
            }else{
                $message = "Not successful. Something went wrong while updating your record!";
            } 
        }
        return $message;
    }
    

}//end class
