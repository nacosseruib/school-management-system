<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Fees;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class SchoolType extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function createFees()
    {   
        $data['allFeesSetup'] = DB::table('school_type')->where('active', 1)->get();
        //
        return view('setup.schoolType', $data);
    }
    
    //
    public function storeFees(Request $request)
    {
         $this->validate($request, [
             'schoolType'          => 'required|string|max:255', 
         ]);
         $schoolTypeID = trim($request->schoolTypeID);
         $success = 0;
         $message = "Sorry, we cannot add new fee now! Try again.";
         if(DB::table('school_type')->where('schooltypeID', $schoolTypeID)->first())
         {  
             //Update Record
              DB::table('school_type')->where('schooltypeID', $schoolTypeID)->update([
                 'school_type'  => $request->schoolType,
                 'description'  => $description,
            ]);
             $message = "Sorry We cannot create your record now! Try again.";
             //Session::forget('feesSetup');
         }else{
             $this->validate($request, [
                 'schoolType'   => 'required|string|max:255|unique:school_type,school_type',  
             ]);
             //Insert New
             DB::table('school_type')->insert([
                 'school_type'  => $schoolType,
                 'description'  => $description,
                 'created_at'   => date('Y-m-d'),
            ]);
             $message = "Record created successfully.";
             //Session::forget('feesSetup');
         }
         if($success){
             return redirect()->route('createFees')->with('message', $message);
         }
         //
         return redirect()->route('createFees')->with('error', $message);
         
    }
 
    //Delete Fee
    public function removeFees($ID)
    {
         $success = 0;
         if(Fees::find($ID)){
             if(StudentFeeSetup::where('feeID', $ID)->where('active', 1)->first())
             {
                $success = 0;
             }else{
                $success = Fees::find($ID)->delete();
             }
         }
         if($success){
             return redirect()->route('createFees')->with('message', 'A record was deleted successfully.');
         }
         //
         return redirect()->route('createFees')->with('error', 'Sorry, we cannot delete this record because is in use.');
    }
     

    // show edit data
    public function editFees($ID)
    {
       if(Fees::find($ID)){
           Session::put('feesSetup', Fees::find($ID));
       }else{
           Session::forget('feesSetup');
       }
       //
       return redirect()->route('createFees')->with('info', 'You can edit your record now.');
       
    }

    // cancel edit
    public function cancelEdit()
    {
       Session::forget('feesSetup');
        //
       return redirect()->route('createFees')->with('message', 'Edit was canceled. You can now add new record.');
    }

    /***********************CLASS FEES SETUP*********************/
    //create class fee setup
    public function createClassFeesSetup() 
    {  
        $data['allActiveFee'] = $this->getAllActiveFees();
        $data['allActiveClass'] = $this->getClass(); 
        $activeFee = array();
        foreach($data['allActiveFee'] as $key=>$listFee)
        {
            foreach($data['allActiveClass'] as $key2=>$listClass)
            {
                $getChecked = ClassFeesSetup::where('feeID', $listFee->feessetupID)->where('classID', $listClass->classID)->value('active');
                $activeFee[$listFee->feessetupID . $listClass->classID] = ($getChecked ? 1: 0);
            }
        }
        $data['activeFeeSetup'] = $activeFee;
        //
        return view('StudentFees.classFeesSetup', $data);
    }

    //store class fee setup
    public function storeClassFeesSetup(Request $request)
    {
        $feeIDClassID  = $request['feeNameAndClassName'];
        $setFeeSetup  = $request['setFeeSetup'];
        if($setFeeSetup)
        {
            foreach ($setFeeSetup as $checked) {
                $arraychecked[] = $checked;
            }
        }else{
            $arraychecked = [];
        }
        
        if($feeIDClassID){
            foreach ($feeIDClassID as $key=>$feeClass) 
            {
                $arrayID    = explode("-", $feeClass);
                $feeID      = $arrayID[0];
                $classID    = $arrayID[1];
                if($arraychecked[$key] == 1)
                {
                    if(!ClassFeesSetup::where('feeID', $feeID)->where('classID', $classID)->first())
                    {
                        //insert
                        $feesSet                = new ClassFeesSetup;
                        $feesSet->feeID         = $feeID;
                        $feesSet->classID       = $classID;
                        $feesSet->active        = 1;
                        $feesSet->created_at    = date('Y-m-d');
                        $feesSet->updated_at    = date('Y-m-d');
                        $feesSet->save();
                    }else{
                        //do nothing...
                    }
                }else{
                    if(ClassFeesSetup::where('feeID', $feeID)->where('classID', $classID)->first())
                    {
                        ClassFeesSetup::where('feeID', $feeID)->where('classID', $classID)->delete();
                    }
                }
            }
        }else{
            return redirect()->route('classFeeSetup')->with('error', 'Sorry, we cannot update you fee now. Try again.');
        }
        return redirect()->route('classFeeSetup')->with('message', 'Your fee setup was updated successfully.');
    }//end function


    /***********************STUDENT FEES SETUP*********************/
    //create student fee setup
    public function createStudentFeesSetup() 
    {  
        $classID            = Session::get('classIDFee'); 
        $studentID          = Session::get('studentIDFee');
        $termID             = Session::get('schoolTerm'); 
        $getSessionID         = Session::get('publicSessionID');
        //Get data from master function
        $feeSetupStudent = $this->getStudentFeesSetupAndBalance($classID, $studentID, $termID, $getSessionID);
        
        $data['studentImagePath']               = $feeSetupStudent['studentImagePath'];
        $data['classNameValue']                 = $feeSetupStudent['classNameValue'];
        $data['studentNameValue']               = $feeSetupStudent['studentNameValue'];
        $data['schoolTermValue']                = $feeSetupStudent['schoolTermValue'];
        $data['schoolTerm']                     = $feeSetupStudent['schoolTerm']; 
        $data['schoolSession']                  = $feeSetupStudent['schoolSession']; 
        $data['allFeesSetup']                   = $feeSetupStudent['allFeesSetup']; 
        $data['termName']                       = $feeSetupStudent['termName'];
        $data['studentDetails']                 = $feeSetupStudent['studentDetails'];
        $data['getAllAssignedFees']             = $feeSetupStudent['getAllAssignedFees'];
        $data['getAllAssignedCoreFees']         = $feeSetupStudent['getAllAssignedCoreFees'];
        $data['getAllAdditionStudentFee']       = $feeSetupStudent['getAllAdditionStudentFee'];
        $data['newCoreStudentFeeAmount']        = $feeSetupStudent['newCoreStudentFeeAmount'];
        $data['newStudentFeeAmount']            = $feeSetupStudent['newStudentFeeAmount'];
        $data['newStudentAdditionalFeeAmount']  = $feeSetupStudent['newStudentAdditionalFeeAmount'];
        //$data['totalAmountDueToBePaidPerStudent'] = $feeSetupStudent['totalAmountDueToBePaidPerStudent'];
        $getAllUpdatedFees = $this->totalStudentFeeToBePaidANDPaidANDBalance($studentID, $classID, $termID, $getSessionID);
        $data['totalAmountDueToBePaidPerStudent']   = $getAllUpdatedFees['totalAmountDueToBePaidPerStudent'];
        $data['totalAmountPaid']                    = $getAllUpdatedFees['totalAmountPaidSoFar'];
        $data['totalBalanceDue']                    = $getAllUpdatedFees['totalBalanceLeft']; 
        $data['getPreviousOutstandingFees']         = $getAllUpdatedFees['getPreviousOutstandingFees'];
        //search current/old student
        $getOldCurrentData = $this->sendOldAndCurrentStudentDataToView();
        //
        return view('StudentFees.studentFeeSetup', $getOldCurrentData, $data);
    }

    //Update/Add new student fee setup - JSON
    public function updateStudentAssignedFeeSetup(Request $request)
    {
        $studentID      = $request['studentID'];
        $studentClassID = $request['classID'];
        $feeID          = $request['feeID'];
        $studentNewFeeAmount  = str_replace(',', '', (number_format($request['amount'], 2)));
        $success = 0;
        if(Student::find($studentID))
        {   
            $message = "Student's fee was updated successfully.";
            if(StudentFeeSetup::where('feeID', $feeID)->where('studentID', $studentID)->first())
            {  
                //Update Record
                $studentFeesSetup                 = StudentFeeSetup::where('feeID', $feeID)->where('studentID', $studentID)->first();
                $studentFeesSetup->feeID          = $feeID;
                $studentFeesSetup->classID        = $studentClassID; 
                $studentFeesSetup->studentID      = $studentID;
                $studentFeesSetup->created_at     = date('Y-m-d');
                $studentFeesSetup->amount         = $studentNewFeeAmount;
                $success                          = $studentFeesSetup->save();
            }else{
                //Insert New
                $studentFeesSetupAddNew                 = new StudentFeeSetup;
                $studentFeesSetupAddNew->feeID          = $feeID;
                $studentFeesSetupAddNew->classID        = $studentClassID; 
                $studentFeesSetupAddNew->studentID      = $studentID;
                $studentFeesSetupAddNew->created_at     = date('Y-m-d');
                $studentFeesSetupAddNew->amount         = $studentNewFeeAmount;
                $success                                = $studentFeesSetupAddNew->save();
            }
        }
        if($success){
            return $message;
        }else{
            return "We are unable to update student's fee! Please try again.";
        }
    }//end function

    public function addMoreFeeForStudentFeeSetup(Request $request)
    {
        $this->validate($request, [
            'feeName'       => 'required|numeric', 
            'studentName'   => 'required|numeric', 
            'className'   => 'required|numeric', 
        ]);
        $studentID      = $request['studentName'];
        $studnetClassID = $request['className'];
        $feeID          = $request['feeName'];
        if(Student::find($studentID) and Fees::find($feeID))
        {   
            if(StudentFeeSetup::where('feeID', $feeID)->where('studentID', $studentID)->where('classID', $studnetClassID)->first())
            {  
                //Update Record
                $addMoreFeeUpdate                 = StudentFeeSetup::where('feeID', $feeID)->where('studentID', $studentID)->where('classID', $studnetClassID)->first();
                $addMoreFeeUpdate->feeID          = $feeID;
                $addMoreFeeUpdate->classID        = $studnetClassID; 
                $addMoreFeeUpdate->studentID      = $studentID;
                $addMoreFeeUpdate->created_at     = date('Y-m-d');
                $addMoreFeeUpdate->additional_fee = 1;
                $success                          = $addMoreFeeUpdate->save();
                $message = "System Successfully updated student's fees. (Select Session from Term to list all student fees.)";
            }else{
                //Insert New
                $addMoreFee                 = new StudentFeeSetup;
                $addMoreFee->feeID          = $feeID;
                $addMoreFee->classID        = $studnetClassID; 
                $addMoreFee->studentID      = $studentID;
                $addMoreFee->created_at     = date('Y-m-d');
                $addMoreFee->additional_fee = 1;
                $addMoreFee->amount         = Fees::find($feeID)->amount;
                $success                    = $addMoreFee->save();
                $message = "System Successfully added new Fee to student's fees. (Select Session from Term to list all student fees.)";
            }
        }else{
            $message = "Sorry, we cannot add this fee for this student now! Please try again.";
        }
        return redirect()->route('studentFeeSetup')->with('message', $message);
    }//end function

    public function removeAdditionalFeeForStudent($ID)
    {
        $studentFeeSetupID = $ID;
        if(StudentFeeSetup::find($studentFeeSetupID))
        {
            if(StudentFeeSetup::find($studentFeeSetupID)->delete())
            {
                $message = "Selected fee was removed from student fees.";
                //
                return redirect()->route('studentFeeSetup')->with('message', $message);
            }else{
                $message = "Sorry, we cannot remove this fee from student fee! Please try again.";
            }
        }else{
            $message = "Sorry, we cannot remove this fee from student fee! Please try again.";
        }
        //
        return redirect()->route('studentFeeSetup')->with('error', $message);
    }



}//end class
