<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentSubject;
use App\Models\StudentClass;
use App\Models\Student; 
use App\Models\Fees; 
use Response;
use File;
use Excel;
use PDF;
use Auth;
use Schema;
use Session;
use DB;

class DailyWeeklyFeeController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    //////////////////////////////////SETUP///////////////////////
    //Create Student Daily, Weekly, Monthly Fee Setup
    public function createStudentDailyWeeklyMonthlyFeeSetup()
    {   
        $data['classID']            = Session::get('classID');
        $data['studentID']          = Session::get('studentID');
        $data['feeID']              = Session::get('feeType');
        $allSetupStident            = array();
        //
        $data['feeName']            = (Fees::find($data['feeID']) ? Fees::find($data['feeID'])->fees_name : "No Fee Selected");
        $data['allClass'] = $this->getClass();
        $data['allStudentList'] = $this->listAllStudentInSchool($data['classID'], $data['studentID']);
        $data['activeDailyFees'] = $this->activeDailyFees();
        foreach($data['allStudentList'] as $key=>$list)
        {   
            $checkStudent = DB::table('daily_fee_setup')->where('studentID', $list->newStudentID)->where('feeID', $data['feeID'])->first();
            $allSetupStident[$key][$list->newStudentID] = ($checkStudent <> null ? 1 : 0);
        }
        $data['allSetupStident'] = $allSetupStident;
        //
        return view('dailyWeeklyFee.setup', $data);
    }
    
    //Process Student Daily, Weekly, Monthly Fee Setup
    public function processStudentDailyWeeklyMonthlyFeeSetup(Request $request)
    {   
         $this->validate($request, [
            //'feeType'           => 'required|integer', 
            'studentName'       => 'required_without_all',
        ]);

        $feeID    = Session::get('feeType');
        if($feeID == null)
        {
            return redirect()->back()->with('message', "Please select fee type from the list above.");
        }
        $allStudentIDArray  = $request['studentName']; 

        if($request['removeAll'] == "Reset"){
            if(is_numeric($feeID) and ($feeID > 0) and is_array($allStudentIDArray) and (count(is_array($allStudentIDArray)) > 0))
            {
                foreach($allStudentIDArray as $eachStudentID) //
                {
                    if(DB::table('daily_fee_setup')->where('studentID', $eachStudentID)->where('feeID', $feeID)->first())
                    {
                       DB::table('daily_fee_setup')->where('studentID', $eachStudentID)->where('feeID', $feeID)->delete();
                        
                    }
                }
            }
            $message = "All selected students were removed from daily, weekly or Monthly fees setup successfully";
        }else{
            $message = "Sorry, we are having issue while trying to process your setup. Please try again.";
            if(is_numeric($feeID) and ($feeID > 0) and is_array($allStudentIDArray) and (count(is_array($allStudentIDArray)) > 0))
            {
                foreach($allStudentIDArray as $eachStudentID) //
                {
                    if(DB::table('daily_fee_setup')->where('studentID', $eachStudentID)->where('feeID', $feeID)->first())
                    {
                        $student = DB::table('daily_fee_setup')->where('studentID', $eachStudentID)->where('feeID', $feeID)->update([
                            'updated_at' =>  date('Y-m-d'),
                        ]);
                        
                    }else
                    {
                        $student = DB::table('daily_fee_setup')->insert([
                            'studentID'     => $eachStudentID,
                            'feeID'         => $feeID,
                            'classID'       => ($this->getStudentDetails($eachStudentID) ? $this->getStudentDetails($eachStudentID)->classID : null),
                            'termID'        => ($this->getSession() ? $this->getSession()->termID : null),
                            'session_code'  => ($this->getSession() ? $this->getSession()->session_code : null),
                            'updated_at'    =>  date('Y-m-d'),
                        ]);
                    }
                    //
                    $message = "Your records were setup successfully";
                }
            }else{
                $message = $message;
            }
        }
        
        //
        return redirect()->route('createDailyFeeSetup')->with('message', $message);
    } 
    
    
    
     //////////////////////////////////MAKE PAYMENT///////////////////////
    //Create Student Daily, Weekly, Monthly Fee Payment
    public function createDailyWeeklyMonthlyPayment()
    {   
        $data['classID']            = Session::get('classID');
        $data['feeID']              = Session::get('feeType');
        $data['feeName']            =(Fees::find($data['feeID']) ? Fees::find($data['feeID'])->fees_name : "No Fee Selected");
        $data['className']          = (StudentClass::find($data['classID']) ? StudentClass::find($data['classID'])->class_name : '');
        $data['studentID']          = Session::get('studentID');
        $data['termName']           = ($this->getSession() ? $this->getSession()->term_name : null);
        $data['sessionCode']        = ($this->getSession() ? $this->getSession()->session_code : null);
        $data['allClass']           = $this->getClass();
        $data['activeDailyFees']    = $this->activeDailyFees();
        //
         $data['allStudentList'] = $this->searchStudentForDailyWeeklyMonthly($data['classID'], $data['studentID'], $data['feeID']);
        //
        return view('dailyWeeklyFee.makePayment', $data);
    }
    
    
    //Process Student Daily, Weekly, Monthly Fee Payment
    public function processDailyWeeklyMonthlyPayment(Request $request)
    {   
         $this->validate($request, [
            //'feeType'           => 'required|integer',  
            'paymentDate'       => 'required|date', 
            'studentName'       => 'required_without_all',
        ]);

        $feeID              = Session::get('feeType'); //$request['feeType'];
        $paymentDate        = $request['paymentDate'];
        $allStudentIDArray  = $request['studentName'];
        $getAllAmount       = $request['amount'];
        $amount             = array();
        foreach($getAllAmount as $allAmount)
        {
            $amount[] = $allAmount;
        }

            $message = "Sorry, we are having issue while trying to process your payment. Please try again.";
            if(is_numeric($feeID) and ($feeID > 0) and is_array($allStudentIDArray) and (count(is_array($allStudentIDArray)) > 0))
            {
                foreach($allStudentIDArray as $key=>$eachStudentID) //
                {
                    if(DB::table('daily_fee_payment')->where('payment_date', $paymentDate)->where('studentID', $eachStudentID)->where('classID', ($this->getStudentDetails($eachStudentID) ? $this->getStudentDetails($eachStudentID)->classID : null))->where('feeID', $feeID)->where('termID', ($this->getSession() ? $this->getSession()->termID : null))->where('session_code', ($this->getSession() ? $this->getSession()->session_code : null))->first())
                    {
                        $student = DB::table('daily_fee_payment')->where('payment_date', $paymentDate)->where('studentID', $eachStudentID)->where('classID', ($this->getStudentDetails($eachStudentID) ? $this->getStudentDetails($eachStudentID)->classID : null))->where('feeID', $feeID)->where('termID', ($this->getSession() ? $this->getSession()->termID : null))->where('session_code', ($this->getSession() ? $this->getSession()->session_code : null))->update([
                            'amount_pay'    =>  ($amount[$key] > 0 ? $amount[$key] : 0),
                            'payment_date'  =>  $paymentDate,
                            'updated_at' =>  date('Y-m-d'),
                        ]);
                        
                    }else
                    {
                        $student = DB::table('daily_fee_payment')->insert([
                            'studentID'     => $eachStudentID,
                            'feeID'         => $feeID,
                            'classID'       => ($this->getStudentDetails($eachStudentID) ? $this->getStudentDetails($eachStudentID)->classID : null),
                            'termID'        => ($this->getSession() ? $this->getSession()->termID : null),
                            'session_code'  => ($this->getSession() ? $this->getSession()->session_code : null),
                            'amount_pay'    =>  ($amount[$key] > 0 ? $amount[$key] : 0),
                            'payment_date'  =>  $paymentDate,
                            'create_at'     =>  date('Y-m-d'),
                            'updated_at'    =>  date('Y-m-d'),
                        ]);
                    }
                    //
                    $message = "Your payments were successfully saved";
                }
            }else{
                $message = $message;
            }
        
        //
        return redirect()->route('createDailyPayment')->with('message', $message);
    } 
    
    
    
    
     //////////////////////////////////REPORT///////////////////////
    //Report Daily, Weekly, Monthly Fee Payment
    public function createDailyWeeklyMonthlyPaymentReport()
    {   
        $termID         = ($this->getSession() ? $this->getSession()->termID : null);
        $termName       = ($this->getSession() ? $this->getSession()->term_name : null);
        $sessionCode    = ($this->getSession() ? $this->getSession()->session_code : null);
        
        $data['classID']            = Session::get('classID');
        $data['className']          = (StudentClass::find($data['classID']) ? StudentClass::find($data['classID'])->class_name : '');
        $data['studentID']          = Session::get('studentID');
        $data['termName']           = $termName;
        $data['sessionCode']        = $sessionCode;
        $data['allClass']           = $this->getClass();
        $data['activeDailyFees']    = $this->activeDailyFees();
        $data['feeType']            = Session::get('feeType');
        $feeDetails                 = Fees::find($data['feeType']);
        $feesOccurentType           = ($feeDetails ? $feeDetails->fees_occurent_type : 0);
        $feeAmountPerPayment        = ($feeDetails ? $feeDetails->amount : 0) ;
        $data['feeName']            =($feeDetails ? $feeDetails->fees_name : "No Fee Selected");
        $data['paymentDate']        = Session::get('reportDate');
        $data['allStudentList'] = $this->searchDailyWeeklyMonthlyReportPayment($data['classID'], $data['studentID'], $data['feeType'], $data['paymentDate']);
        
        //DAYS OF THE MONTH WITHOUT WEEKENDS
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month = date("n", strtotime($data['paymentDate'] == null ? date('n') : $data['paymentDate'])); //date('n'); // Month ID, 1 through to 12.
        $year = date("Y", strtotime($data['paymentDate'] == null ? date('Y') : $data['paymentDate'])); //date('Y'); // Year in 4 digit 2009 format.
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {
            $date = $year.'/'.$month.'/'.$i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
            //if not a weekend add day to array
            if($day_name == 'Sun')
            {
                $workdays[] = 'SUN';
            }else if($day_name == 'Sat'){
                $workdays[] = 'SAT';
            }else{
                $workdays[] = $i.$this->ordinal_suffix($i);
            }
            if($day_name != 'Sun' && $day_name != 'Sat'){
                $getOnlyWorkdays[] = $i;
            }
        }
        
        $data['workdays']           = $workdays;
        $data['getOnlyWorkdays']    = $getOnlyWorkdays;
        $totalAmountPerMonthly = 0;
        if($feesOccurentType == 7){ //Daily
            $totalAmountPerMonthly = count($data['getOnlyWorkdays']) * $feeAmountPerPayment;
        }else if($feesOccurentType == 6){ //Weekly
            $totalAmountPerMonthly = $this->weekOfMonth($data['paymentDate']) * $feeAmountPerPayment;
        }else if($feesOccurentType == 5){ //Monthly
            $totalAmountPerMonthly = $feeAmountPerPayment;
        }
        $data['feeAmountPerPayment'] = $feeAmountPerPayment;
        $data['totalAmountPerMonthly']   = $totalAmountPerMonthly;
        $studentAmount = array();
        foreach($data['allStudentList'] as $studentKey => $listStudent)
        {
            foreach ($data['workdays'] as $daysKey=>$value)
            {
                $studentAmount[$daysKey][$listStudent->newStudentID] = DB::table('daily_fee_payment')
                    ->where('feeID', $data['feeType'])
                    ->where('studentID', $listStudent->newStudentID)
                    ->where('termID', $termID)
                    ->where('session_code', $sessionCode)
                    ->where('classID', $listStudent->classID)
                    ->where('payment_date', $year.'-'.$month.'-'.$value)
                    ->value('amount_pay');
            }
        }
        $data['studentAmount'] = $studentAmount;
        //
        
        return view('dailyWeeklyFee.report', $data);
    }
    
    
    //DAILY REPORT
    public function createDailyOnlyPaymentReport()
    {   
        $termID         = ($this->getSession() ? $this->getSession()->termID : null);
        $termName       = ($this->getSession() ? $this->getSession()->term_name : null);
        $sessionCode    = ($this->getSession() ? $this->getSession()->session_code : null);
        
        $data['classID']            = Session::get('classID');
        $data['className']          = (StudentClass::find($data['classID']) ? StudentClass::find($data['classID'])->class_name : ' ALL ');
        $data['studentID']          = Session::get('studentID');
        $data['termName']           = $termName;
        $data['sessionCode']        = $sessionCode;
        $data['allClass']           = $this->getClass();
        $data['activeDailyFees']    = $this->activeDailyFees();
        $data['feeType']            = Session::get('feeType');
        $feeDetails                 = Fees::find($data['feeType']);
        $feesOccurentType           = ($feeDetails ? $feeDetails->fees_occurent_type : 0);
        $feeAmountPerPayment        = ($feeDetails ? $feeDetails->amount : 0) ;
        $data['feeAmountPerPayment'] = $feeAmountPerPayment;
        $data['feeName']            =($feeDetails ? $feeDetails->fees_name : "No Fee Selected");
        $data['paymentDate']        = Session::get('reportDate');
        $data['allStudentList'] = $this->searchDailyWeeklyMonthlyReportPayment($data['classID'], $data['studentID'], $data['feeType'], $data['paymentDate']);
        
        $studentAmount = array();
        foreach($data['allStudentList'] as $studentKey => $listStudent)
        {
            $studentAmount[$studentKey][$listStudent->newStudentID] = DB::table('daily_fee_payment')
                ->where('feeID', $data['feeType'])
                ->where('studentID', $listStudent->newStudentID)
                ->where('termID', $termID)
                ->where('session_code', $sessionCode)
                ->where('classID', $listStudent->classID)
                ->where('payment_date', $data['paymentDate'])
                ->value('amount_pay');
            
        }
        $data['studentAmount'] = $studentAmount;
        //
        
        return view('dailyWeeklyFee.weeklyReport', $data);
    }
    
   
    public function generateDailyWeeklyMonthlyPaymentReport()
    {  
        
    }
    
    //Search student
    public function searchStudentList(Request $request)
    {
        Session::forget('classID');
        Session::forget('studentID');
        //
        Session::put('classID', $request['className']);
        Session::put('studentID', $request['studentName']);
        Session::put('reportDate', $request['paymentDate']);
        Session::put('feeType', $request['feeType']);
        //
        return redirect()->back(); //route('viewAllStudent')
    }
    


}//end class
