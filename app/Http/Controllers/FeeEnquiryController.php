<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Fees;
use App\Models\ClassFeesSetup;
use App\Models\ClassFeesSetupHistory;
use App\Models\StudentFeeSetup;
use App\Models\Student;
use App\Models\StudentClass;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class FeeEnquiryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    //create student fee enquiry
    public function createFeeEnquiry() 
    {  
        $classID                = Session::get('getClassID'); 
        $getTermID              = Session::get('getTermID');
        $getSession             = ($this->getSession() ? $this->getSession()->session_code : null); 
        //Get data from master function
        $feeSetupStudent = $this->getClassFeesSetupEnquiry($classID, $getTermID, $getSession);
        
        $data['classNameValue']                 = $feeSetupStudent['className'];
        $data['classID']                        = $feeSetupStudent['classNameValue'];
        $data['schoolTermValue']                = $feeSetupStudent['schoolTermValue'];
        $data['schoolTerm']                     = $this->getTerm();  
        $data['schoolSession']                  = $feeSetupStudent['schoolSession']; 
        $data['allFeesSetup']                   = $feeSetupStudent['allFeesSetup']; 
        $data['termID']                         = $getTermID;
        $data['termName']                       = $feeSetupStudent['termName'];
        $data['getAllAssignedFees']             = $feeSetupStudent['getAllAssignedFees'];
        $data['getAllAssignedCoreFees']         = $feeSetupStudent['getAllAssignedCoreFees'];
        $data['allClass']                       = $this->getClass(); 
        // 
        return view('FeeEnquiry.feeEnquiry', $data);
    }

    
    public function searchClassFeeEnquiry(Request $request)
    {
        Session::put('getClassID', $request['className']); 
        Session::put('getTermID', $request['schoolTerm']);
        
        return redirect()->back();
    }
    

}//end class
