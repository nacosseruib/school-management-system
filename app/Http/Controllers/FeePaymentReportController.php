<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Fees;
use App\Models\ClassFeesSetup;
use App\Models\StudentFeeSetup;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentPaymentFee;
use App\Models\Term;
use App\Models\SchoolSession;
use App\Models\PublicSession;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class FeePaymentReportController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createFeePaymentReport()
    {   
        
        $classID            = Session::get('classIDFee'); 
        $studentID          = Session::get('studentIDFee');//Not Use
        $termID             = Session::get('schoolTerm'); 
        $publicSessionID    = Session::get('publicSessionID');
        $getSessionID       = ($publicSessionID ? $publicSessionID : $this->getSession()->session_code );  //((PublicSession::find($publicSessionID) and $this->getSession()) ? PublicSession::find($publicSessionID)->session_name : ($publicSessionID == null and $this->getSession() ? $this->getSession()->session_code : $publicSessionID) ); 
        $reportType         = Session::get('reportType');
        //
        $dataReport = $this->getAllPaymentReport($classID, $termID, $getSessionID, $reportType);
        //search current/old student
        $searchData = $this->sendOldAndCurrentStudentDataToView();
        //
        return view('StudentFees.feePaymentReport', $dataReport, $searchData);
    }
    
    
    //Student payment Receipt
    public function studentPaymentReceipt($recordID)
    {
         $data['getFeesDetails'] = array();
         $data['getDetails'] = array();
         if(StudentPaymentFee::where('transactionID', $recordID)->where('active', 1)->first())
         {  
             $data['getDetails'] = StudentPaymentFee::where('student_payment_fees.transactionID', $recordID)
                    ->join('student_class', 'student_class.classID', '=', 'student_payment_fees.classID')
                    ->join('term', 'term.termID', '=', 'student_payment_fees.termID')
                    ->where('student_payment_fees.active', 1)
                    ->first();
                                        
            $payment = StudentPaymentFee::where('transactionID', $recordID)->where('active', 1)->first();
            
            ///////GET CORE AMOUNT////////////
            $data['getSessionCoreFees'] = DB::table('class_fees_setup_history')->where('class_fees_setup_history.active', 1 )
                ->leftjoin('fees', 'fees.feessetupID', '=', 'class_fees_setup_history.feeID')
                ->where('class_fees_setup_history.session_code', $payment->session_code)
                ->where('class_fees_setup_history.classID', $payment->classID)
                ->where('class_fees_setup_history.termID', 4 )
                ->select('*', 'class_fees_setup_history.fees_name as feesNameHistory', 'class_fees_setup_history.fee_amount as feesSetupAmount')
                ->groupBy('class_fees_setup_history.feeID')
                ->get();
            $data['getSessionCoreFees'] = ($data['getSessionCoreFees'] ? $data['getSessionCoreFees'] : []);
            $coreAmount = array();
            $getCoreAmount = 0;
            foreach($data['getSessionCoreFees'] as $coreKey => $getAmount)
            {   
                $getCoreAmount = StudentFeeSetup::where('student_fees_setup.studentID', $payment->studentID)
                        ->where('student_fees_setup.session_code', $payment->session_code)
                        ->where('student_fees_setup.classID', $payment->classID)
                        ->where('student_fees_setup.termID', $payment->termID )
                        ->where('student_fees_setup.feeID', $getAmount->feeID )
                        ->value('amount');
                $coreAmount[$coreKey] = ($getCoreAmount <> '' ? $getCoreAmount : $getAmount->fee_amount);
            }
            $data['coreAmount'] = $coreAmount;
           
            ///////GET TERM AMOUNT ////////////
            $data['getTermFees'] = DB::table('class_fees_setup_history')
                ->leftjoin('fees', 'fees.feessetupID', '=', 'class_fees_setup_history.feeID')
                ->leftjoin('student_fees_setup', 'student_fees_setup.feeID', '=', 'class_fees_setup_history.feeID')
                ->where('class_fees_setup_history.session_code', $payment->session_code)
                ->where('class_fees_setup_history.classID', $payment->classID)
                ->where('class_fees_setup_history.termID', $payment->termID)
                ->select('*', 'class_fees_setup_history.fees_name as feesNameHistory', 'class_fees_setup_history.fee_amount as feesSetupAmount', 'student_fees_setup.amount as studentAmount')
                ->groupBy('class_fees_setup_history.feeID')
                ->get();
            $data['getTermFees'] = ($data['getTermFees'] ? $data['getTermFees'] : []);
            $termAmount = array();
            $getTermAmount = 0;
            foreach($data['getTermFees'] as $termKey => $getAmount)
            {   
                $getTermAmount = StudentFeeSetup::where('student_fees_setup.studentID', $payment->studentID)
                        ->where('student_fees_setup.session_code', $payment->session_code)
                        ->where('student_fees_setup.classID', $payment->classID)
                        ->where('student_fees_setup.termID', $payment->termID )
                        ->where('student_fees_setup.feeID', $getAmount->feeID )
                        ->value('amount');
                $termAmount[$termKey] = ($getTermAmount <> '' ? $getTermAmount : $getAmount->fee_amount);
            }
            $data['termAmount'] = $termAmount;
            
            ///////GET ADDITIONAL AMOUNT////////////
            $data['getAdditionalFees'] = DB::table('student_fees_setup')
                ->leftjoin('fees', 'fees.feessetupID', '=', 'student_fees_setup.feeID')
                ->where('student_fees_setup.additional_fee', 1)
                ->where('student_fees_setup.studentID', $payment->studentID)
                ->where('student_fees_setup.session_code', $payment->session_code)
                ->where('student_fees_setup.classID', $payment->classID)
                ->where('student_fees_setup.termID', $payment->termID )
                ->select('*', 'student_fees_setup.fees_name as studentFeesName', 'fees.amount as feesSetupAmount', 'student_fees_setup.amount as studentAmount')
                ->groupBy('student_fees_setup.feeID')
                ->get();

         }else{
             return redirect()->back()->with('error', 'Sorry, we are having issue while trying to generate your payment receipt!');
         }
         
         //
         return view('StudentFees.paymentReceipt', $data);
     }
     
    

}//end class
