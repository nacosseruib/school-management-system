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
use App\Models\SchoolSession;
use App\Models\PublicSession;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class StudentFeePaymentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createStudentFeePayment()
    {   
        $classID            = Session::get('classIDFee'); 
        $studentID          = Session::get('studentIDFee');
        $termID             = Session::get('schoolTerm'); 
        $publicSessionID    = Session::get('publicSessionID');
        $getSessionID       =  $this->returnSessionCode($publicSessionID);
        $data['allFeesSetup'] = $this->getAllFees();
        //Get data from master function
        $feeSetupStudent = $this->getStudentFeesSetupAndBalance($classID, $studentID, $termID, $getSessionID);
        $data['studentImagePath']               = $feeSetupStudent['studentImagePath'];
        $data['classNameValue']                 = $feeSetupStudent['classNameValue'];
        $data['studentNameValue']               = $feeSetupStudent['studentNameValue'];
        $data['schoolTermValue']                = $feeSetupStudent['schoolTermValue'];
        $data['schoolTerm']                     = $feeSetupStudent['schoolTerm']; 
        $data['schoolSession']                  = $feeSetupStudent['schoolSession']; 
        $data['termName']                       = $feeSetupStudent['termName'];
        $data['studentDetails']                 = $feeSetupStudent['studentDetails'];
        $data['getAllAdditionStudentFee']       = $feeSetupStudent['getAllAdditionStudentFee'];
        $data['newStudentAdditionalFeeAmount']  = $feeSetupStudent['newStudentAdditionalFeeAmount'];
        Session::put('totalAmountToPaid', $feeSetupStudent['totalAmountDueToBePaidPerStudent']);
        Session::put('getStudentDetails', $feeSetupStudent['studentDetails']);
        $getAllUpdatedFees = $this->totalStudentFeeToBePaidANDPaidANDBalance($studentID, $classID, $termID, $getSessionID);
        $data['totalAmountDueToBePaidPerStudent']   = $getAllUpdatedFees['totalAmountDueToBePaidPerStudent'];
        $data['totalAmountPaid']                    = $getAllUpdatedFees['totalAmountPaidSoFar'];
        $data['totalBalanceDue']                    = $getAllUpdatedFees['totalBalanceLeft'];
        $data['allPaymentHistory']                  = $getAllUpdatedFees['paymentHistory']; 
        $data['totalPreviousDue']                   = $getAllUpdatedFees['getPreviousOutstandingFees']; 
        //search current/old student
        $getOldCurrentData = $this->sendOldAndCurrentStudentDataToView();
 
        return view('StudentFees.studentPaymentFee', $getOldCurrentData, $data);
    }
    

    // STORE OR UPDATE PAYMENT
    public function storeStudentFeePayment(Request $request)
    {   
         $this->validate($request, [
             'amountToPay'          => 'required|numeric|max:99999999.9',
             'paymentDate'          => 'date|max:10',
         ]);
         if(strlen($request->paymentDescription) > 41){ // 41 : Max Char for payment description
            $this->validate($request, [
                'paymentDescription'  => 'required|string|max:41',
            ]);
         }
        $classID            = Session::get('classIDFee'); 
        $studentID          = Session::get('studentIDFee');
        $termID             = Session::get('schoolTerm'); 
        $publicSessionID    = Session::get('publicSessionID');
        $getSessionID       = $this->returnSessionCode($publicSessionID); 
         //dd($studentID .'-'. $classID .'-'. $termID);
         $paidBy                = Auth::User()->name .' '. Auth::User()->other_name;
         $amountToPay           = (is_numeric($request->amountToPay) ? str_replace(',', '', number_format($request->amountToPay, 2)) : 0);
         $balance               = 0;
         $description           = $request->paymentDescription;
         $paymentDate           = $request->paymentDate;

         $paymentUpdated = $this->totalStudentFeeToBePaidANDPaidANDBalance($studentID, $classID, $termID, $getSessionID);
         if( empty($studentID) or empty($termID) or ($amountToPay < 1)  or ($amountToPay > $paymentUpdated['totalBalanceLeft']))
         {
            return redirect()->back()->with('error', 'Zero Amount not allowed OR Amount to be paid is greater than the outstanding to be paid OR Student details not found!');
         }
         //Calculate Balance and Paid
         $totalAmountToPaid     = $paymentUpdated['totalAmountDueToBePaidPerStudent'];
         $totalPaid             = $paymentUpdated['totalAmountPaidSoFar'];
         $totalOutstanding      = $paymentUpdated['totalBalanceLeft']; 
         $balance               = ($totalOutstanding - $amountToPay);

         $success = 0;
         $message = "Sorry, We cannot find student's details! So, payment was aborted. Please select student again.";
         if(($amountToPay <= $paymentUpdated['totalBalanceLeft']) and (Student::find($studentID) and $classID))
         {  
            $paidByName         = Auth::User()->name .' '. Auth::User()->other_name;
            $paidByEmail        = Auth::User()->email;
            $paidByPhone        = Auth::User()->telephone;
            $platformPaidFrom   = 'Web Payment';
            $paymentStatus      = 'Success';
            $paymentStatusCode  = 200;

            //Insert New Fee
            $success = $this->PayFeeInsert( $studentID, $classID, $termID, $getSessionID, $description, $paymentDate, $totalAmountToPaid, $balance, $amountToPay, $paidByName, $paidByEmail, $paidByPhone, $platformPaidFrom, $paymentStatus, $paymentStatusCode);
            $message = "Student fee was accepted and paid successfully.";

         }else{
            $message = "It seems the amount you want to pay is greater than the balance to be paid! Please review and try again.";
         }
         if($success){
            return redirect()->route('createStudentFeePayment')->with('message', $message);
         }else{
            return redirect()->route('createStudentFeePayment')->with('error', $message);
         }
    }//

 
    //Delete payment history
    public function deleteStudentPaymentHistory($ID)
    {
         $success = 0;
         if(StudentPaymentFee::find($ID)){
             $success = StudentPaymentFee::find($ID)->delete();
         }
         if($success){
             return redirect()->route('createStudentFeePayment')->with('message', 'A record was deleted successfully from student payment history.');
         }
         //
         return redirect()->route('createStudentFeePayment')->with('error', 'Sorry, we cannot delete this payment history! Please tyr again.');
     }
    

}//end class
