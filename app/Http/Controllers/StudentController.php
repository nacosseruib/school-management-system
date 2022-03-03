<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentSubject;
use App\Models\StudentClass;
use App\Models\Student;
use App\Exports\ExportStudent;
use App\Imports\ImportStudent;
use App\Models\TempStudent;
use App\Models\RegistrationFormat;
use App\Models\SchoolProfile;
use App\Exports\ExportBasicStudent;
use App\Exports\ExportFullStudent;
use Response;
use File;
use Excel;
use PDF;
use Auth;
use Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StudentController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function createStudent()
    {
        Session::forget('classID');
        Session::forget('studentID');
        //
        $data['allClass'] = $this->getClass();
        $data['schoolType'] = DB::table('school_type')->where('active', 1)->orderBy('school_type_name', 'Asc')->get();
        $data['allStudent'] = $this->getStudent();
        $data['allExtraCurricular'] = $this->getExtraCurricular();
        $getReg = $this->getStudentRegNo(0);
        $data['registrationNo'] = $getReg['studentRegID'];
        $data['checkAutoRegNo'] = $this->checkIFRegIDAutoIsOnOff();
        //
        return view('student.createStudent', $data);
    }


    //print student
    public function printStudent()
    {
        $data['allStudentList'] = $this->getStudent();
        //
        return view('student.printStudent', $data);
    }

    //Export student - Download
    public function exportBasicStudent($type)
    {
       return Excel::download(new ExportBasicStudent, date('d-m-Y-') . time() .'-Basic-Student-Details.' . $type);
    }

    //Export student - Download
    public function exportFullStudent($type)
    {
       return Excel::download(new ExportFullStudent, date('d-m-Y-') . time() .'-Full-student-Details.' . $type);
    }

    //Export student - Download
    public function exportBasicStudentPDF()
    {
        $data['allStudentList'] = $this->getAllActiveStudent();
        $pdf  = PDF::loadView('student.printStudentPDF', $data);
        //
        return $pdf->download(date('d-m-Y-') . time() .'-Basic-student-Details.pdf');
    }


    //Process New Student
    public function storeStudent(Request $request)
    {
        $this->validate($request, [
            'studentAdmittedDate'   => 'required|string|max:50',
            'surname'               => 'required|string|max:50',
            'otherName'             => 'required|string|max:50',
            'gender'                => 'required|string|max:15',
            'studentAddress'        => 'required|string|max:200',
            'className'             => 'required|string|max:100',
        ]);
        if($this->checkIFRegIDAutoIsOnOff() == 0)
        {
            $this->validate($request, [
                'studentRegistrationId' => 'required|string|max:255|unique:student,student_regID',
                'rollNumber'            => 'required|numeric|max:100',
            ]);
        }
        $file       = $request->file('file');
        if(!empty($file) or $file != "")
        {
          $this->validate($request, [
            'file' => 'required|image|mimes:png,jpg,jpe,jpeg|max: 3072',
          ]);
        }
        //get current student RegNo
        if($this->checkIFRegIDAutoIsOnOff() == 1)
        {
            $getReg = $this->getStudentRegNo(trim($request->className));
            $registrationNo = $getReg['studentRegID'];
            $roll = $getReg['roll'];
        }else{
            $registrationNo = $request->studentRegistrationId;
            $roll = $request->rollNumber;
        }

        $student = new Student;
        $student->student_regID         = $registrationNo;
        $student->student_roll          = $roll;
        $student->admitted_date         = $request->studentAdmittedDate;
        $student->student_class         = $request->className;
        $student->student_firstname     = $request->otherName;
        $student->student_lastname      = $request->surname;
        $student->student_gender        = $request->gender;
        $student->student_address       = $request->studentAddress;
        $student->parent_firstname      = $request->parentFirstName;
        $student->parent_lastname       = $request->parentLastName;
        $student->parent_address        = $request->parentAddress;
        $student->parent_telephone      = $request->parentTelephone;
        $student->parent_email          = $request->parentEmail;
        $student->parent_occupation     = $request->parentOccupation;
        $student->date_of_birth         = $request->dateOfBirth;
        $student->religion              = $request->religion;
        $student->nationality           = $request->nationality;
        $student->state                 = $request->state;
        $student->home_town             = $request->homeTown;
        $student->school_type           = $request->schoolType;
        $student->created_at            = date('Y-m-d');
        $student->admitted_class        = (StudentClass::find($request->className) ? StudentClass::find($request->className)->class_name : '');
        $student->admitted_session      = ($this->getSession() ? $this->getSession()->session_code : '');
        if($student->save()){
            //add extra
            $getStudentID = null;
            $getStudentID = Student::orderBy('studentID', 'Desc')->value('studentID');
            if(!empty($request->extraCurricular)){
                foreach($request->extraCurricular as $convertToArray){
                    $arrayExtra[] = $convertToArray;
                }
                foreach($arrayExtra as $extra){
                    ($extra) ? (DB::table('student_extra')->insert(['extraID'=>$extra, 'studentID'=> $getStudentID])) : '';
                }
            }
            //upload photo
            $getPath    = 'public/appAssets/passport/student/';
            if($file){
                $originalExtension      = $file->getClientOriginalExtension();
                $imageNewName           = $this->randomUniqueCode() . '.' .$originalExtension;
                $path  = $this->uploadBasePath() . '/' . $getPath;
                if($file->move($path, $imageNewName))
                {
                    Student::where('studentID', $getStudentID)->update(['photo'=>$imageNewName]);
                }
            }

            //send SMS
            $parentNumber   = (Student::orderBy('studentID', 'Desc')->first() ? Student::orderBy('studentID', 'Desc')->value('parent_telephone') : '');
            $studentID   = (Student::orderBy('studentID', 'Desc')->first() ? Student::orderBy('studentID', 'Desc')->value('studentID') : '');
            if($parentNumber <> '')
            {
                try{
                    $className = (StudentClass::find($request->className) ? StudentClass::find($request->className)->class_name : '');
                    $schoolShortName = ($this->schoolProfile() ? $this->schoolProfile()->school_short_name : 'SchlEPortal');
                    $smsMessage     = 'Congrats parent! ' . $student->surname .' '. $request->otherName . ' has been admitted to ' . $className . '. Pls accept our congratulations wishes for his/her new chapter ahead. Thks';
                    $this->SEND_SMS_WITH_TWILIO_SMS_API($parentNumber, $smsMessage);
                } catch (Exception $e){}
            }

            if(Student::find($studentID))
            {
                 return redirect()->route('printAdmissionLetter', ['ID'=>$studentID])->with('message', 'New student was created successfully. You can print this student admission letter now. Thanks');
            }else{
                 return redirect()->route('createStudent')->with('message', 'New student was created successfully.');
            }

        }
        return redirect()->route('createStudent')->with('error', 'Sorry, we cannot create new student! Try again.');

    }

    //create update student
    public function createUpdateStudent($studentID)
    {
        $data['schoolType'] = DB::table('school_type')->where('active', 1)->orderBy('school_type_name', 'Asc')->get();
        if(($studentID == null) or !Student::find($studentID))
        {
            return redirect()->route()->with('error', "Sorry, we are having problem loading the selected student's details!");
        }else{
            $data['allClass'] = $this->getClass();
            $data['student'] = $this->pickOneStudent($studentID);
            $data['allExtraCurricular'] = $this->getExtraCurricular();
            $studentName = Student::where('studentID',$studentID)->select('student_firstname','student_lastname')->first();
            //$data['path']    = 'appAssets/passport/student/';
            $data['path'] = $this->studentImagePath();
            $data['checkAutoRegNo'] = $this->checkIFRegIDAutoIsOnOff();
            $getReg = $this->getStudentRegNo(0);
            $data['registrationNo'] = $getReg['studentRegID'];
            //
            return view('student.editStudent', $data)->with('message', 'You are about to edit <b>'. $studentName->student_firstname .' '. $studentName->student_lastname .'</b> record !');
        }
        return redirect()->back()->with('error', "Sorry, will cannot find this student's details");
    }

    //
    public function updateStudentDetails(Request $request)
    {
        $studentID = $request['studentID'];
        if(Student::find($studentID))
        {
            if($this->checkIFRegIDAutoIsOnOff() == 0)
            {
                $this->validate($request, [
                    'studentRegistrationId' => 'required|string|max:200',
                    'rollNumber'            => 'required|numeric|max:100',
                ]);
            }
            $this->validate($request, [
                //'studentAdmittedDate'   => 'required|string|max:50',
                'surname'               => 'required|string|max:50',
                'otherName'             => 'required|string|max:50',
                'gender'                => 'required|string|max:15',
                //'studentAddress'        => 'required|string|max:250',
                'className'             => 'required|string|max:100',
            ]);

            if(Student::where('studentID', '<>', $studentID)->where('student_regID', $request->studentRegistrationId)->first())
            {
                return redirect()->back()->with('error', 'Sorry, this registration number is in use by another student! Try another Reg. No.');
            }

            $file  = $request->file('file');
            if(!empty($file) or $file != "")
            {
                $this->validate($request, [
                    'file'          => 'required|image|mimes:png,jpg,jpe,jpeg|max: 3072',
                ]);
            }
            $student = Student::find($request['studentID']);
            $student->student_regID         = $request->studentRegistrationId;
            $student->student_roll          = $request->rollNumber;
            $student->admitted_date         = $request->studentAdmittedDate;
            $student->student_class         = $request->className;
            $student->student_firstname     = $request->otherName;
            $student->student_lastname      = $request->surname;
            $student->student_gender        = $request->gender;
            $student->student_address       = $request->studentAddress;
            $student->parent_firstname      = $request->parentFirstName;
            $student->parent_lastname       = $request->parentLastName;
            $student->parent_address        = $request->parentAddress;
            $student->parent_telephone      = $request->parentTelephone;
            $student->parent_email          = $request->parentEmail;
            $student->parent_occupation     = $request->parentOccupation;
            $student->date_of_birth         = $request->dateOfBirth;
            $student->religion              = $request->religion;
            $student->nationality           = $request->nationality;
            $student->state                 = $request->state;
            $student->home_town             = $request->homeTown;
            $student->school_type           = $request->schoolType;
            $student->updated_at            = date('Y-m-d');
            if($student->save()){
                //add extra
                $getStudentID = null;
                $getStudentID = Student::where('studentID', $studentID)->value('studentID');
                if(!empty($request->extraCurricular)){
                    foreach($request->extraCurricular as $convertToArray){
                        $arrayExtra[] = $convertToArray;
                    }
                    DB::table('student_extra')->where('studentID', $getStudentID)->delete();
                    foreach($arrayExtra as $extra){
                        ($extra) ? (DB::table('student_extra')->insert(['extraID'=>$extra, 'studentID'=> $getStudentID])) : '';
                    }
                }
                //upload photo
                $getPath = 'public/appAssets/passport/student/';
                if($file){
                    $originalExtension      = $file->getClientOriginalExtension();
                    $imageNewName           = $this->randomUniqueCode() . '.' .$originalExtension;
                    $path               = $this->uploadBasePath() . $getPath;
                    if($file->move($path, $imageNewName))
                    {
                        $getOldPath = Student::where('studentID', $getStudentID)->value('photo');
                        Student::where('studentID', $getStudentID)->update(['photo'=>$imageNewName]);
                        if(file_exists($path . $getOldPath))
                        {
                            //unlink($path . $getOldPath);
                        }
                    }
                }
                return redirect()->route('editStudent', ['studentID'=>$studentID])->with('message', 'Student record was updated successfully. You may click on cancel button to go back to list.');
            }
        }
        return redirect()->route('editStudent', ['studentID'=>$studentID])->with('error', 'Sorry, we cannot update this record! Try again.');

    }



    //View all students
    public function listAllStudent()
    {
        $classID = Session::get('classID');
        $studentID = Session::get('studentID');
        //
        $data['allClass'] = $this->getClass();
        $data['allStudentList'] = $this->listAllStudentInSchool($classID, $studentID);
        $data['allExtraCurricular'] = $this->getExtraCurricular();
        //
        return view('student.listStudent', $data);
    }

    //Search student
    public function searchStudentList(Request $request)
    {
        Session::forget('classID');
        Session::forget('studentID');
        //
        Session::put('classID', $request['className']);
        Session::put('studentID', $request['studentName']);
        //
        return redirect()->back(); //route('viewAllStudent')
    }



    //View - Student Details
    public function studentDetails($studentID)
    {
        //clearstatcache();
        if(Student::find($studentID))
        {
            $data['student'] = Student::where('student.studentID', $studentID)
            ->join('student_class', 'student_class.classID', '=', 'student.student_class')
            ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student.student_curricularID')
             ->leftJoin('school_type', 'school_type.schooltypeID', '=', 'student.school_type')
            ->select('*', 'student.created_at as studentDate')
            ->first();
            //
            $data['studentExtra'] = DB::table('student_extra')->where('studentID', $studentID)
               ->leftJoin('extra_curricular', 'extra_curricular.curricularID', '=', 'student_extra.extraID')
               ->get();

            $data['path'] = $this->studentImagePath();

            return view('student.viewStudent', $data);
        }else{
            return redirect()->back()->with('error', 'Sorry, the student you are trying to look for cannot be found! Please try again.');
        }
    }


    //Delete student
    public function removeStudent($ID = null)
    {
        $student = new Student;
        $success = 0;
        if(Student::find($ID)){
            $success = Student::where('studentID', $ID)->update(['active'=>0, 'deleted'=>1, 'student_regID'=>'']);
        }
        if($success){
            return redirect()->route('createStudent')->with('message', 'A student was removed and moved to recycle bin successfully.');
        }
        return redirect()->route('createStudent')->with('error', 'Sorry, we cannot remove this student from our system. Try again');

    }


    //Activate or Deactivate student
    public function activateDeactivateStudent($studentID, $statusID)
    {
        $message = "Sorry, we are having issue while updating your record. Try againg later.";
        if(Student::find($studentID))
        {
            $student = Student::find($studentID);
            $student->active = $statusID;
            $student->deleted = 0;
            $student->graduate = 0;
            $student->withdraw = 0;
            $student->updated_at = date('Y-m-d');
            if($student->save()){
                $message = "Your record was updated successfully.";
            }else{
                $message = "Not successful. Something went wrong while updating your record!";
            }

        }
        return $message;
    }


    //EXCEL IMPORT AND DOWNLOAD
    public function createImportExport()
    {
        $data['tempStudentExcel'] = $this->viewImportExcelData();
        return view('student.excelImportStudent', $data);
    }

    //Download
    public function downloadExcel($type)
    {
       return Excel::download(new ExportStudent, date('d-m-Y') .'-Imported-Student-Excel-File.' . $type);
    }

    //Download New
    public function downloadNewExcel($type)
    {
        return Excel::download(new TempStudent, 'Blank-Excel-File.' . $type);
    }


    //Import Excel
    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'excelStudentFile' => 'required',
        ]);
        TempStudent::truncate();
        $path = $request->file('excelStudentFile');
        Excel::import(new ImportStudent, $path);

        return back()->with('message', 'Student details were extracted successfully from file.');
    }

     //view Import Data
     public function viewImportExcelData()
     {
         return TempStudent::get();
     }

    //Delete Row from Import data
    public function deleteRowFromImportData($getID)
    {

        if(($getID <> null) and TempStudent::find($getID))
        {
            $student = TempStudent::find($getID);
            $student->delete();
             //
            return back()->with('message', 'A record was removed from the list.');
        }else{
            //
            return back()->with('error', 'Sorry, we cannot remove this record from our system!  Please try again.');
        }

    }





    //Create Student Promotion
    public function createStudentPromotion()
    {
        $classID = Session::get('classID');
        $studentID = Session::get('studentID');
        //
        $data['allClass'] = $this->getClass();
        $data['allStudentList'] = $this->listAllStudentInSchool($classID, $studentID);
        $data['allExtraCurricular'] = $this->getExtraCurricular();
        //
        return view('student.studentPromotion', $data);
    }

    //Process Student Promotion
    public function storeStudentPromotion(Request $request)
    {
         $this->validate($request, [
            'studentNewClass'   => 'required|string',
            'studentName'       => 'required_without_all',
        ]);
        $countTotalPromoted = 0;
        $studentNewClass    = $request['studentNewClass'];
        $allStudentIDArray  = $request['studentName'];

        $countTotalPromoted = $this->studentPromotion($allStudentIDArray, $studentNewClass);

        if($countTotalPromoted){
            $message = $countTotalPromoted . " Student was/were promoted (or Graduated/withdrawn) successfully to ". $this->getClassNameOnly($studentNewClass) ." class";
        }else{
            $message = " Not successful. Something went wrong while processing your reocrd! NOTE: ". $countTotalPromoted ." Student was/were promoted to " .$this->getClassNameOnly($studentNewClass);
        }
        //
        return redirect()->route('createStudentPromotion')->with('message', $message);
    }



     //Register student from import excel
     public function registerStudentImportExcel()
     {
        $tempStudent = TempStudent::get();
        $totalInsertion = 0;
        if($tempStudent)
        {
            foreach($tempStudent as $value)
            {
                if(!Student::where('student_regID', $value->student_regID)->first())
                {
                    $student = new Student;
                    $student->student_regID         = $value->student_regID;
                    $student->student_roll          = $value->student_roll;
                    $student->admitted_date         = $value->admitted_date;
                    $student->student_class         = $value->student_class;
                    $student->student_firstname     = $value->student_firstname;
                    $student->student_lastname      = $value->student_lastname;
                    $student->student_gender        = $value->student_gender;
                    $student->student_address       = $value->student_address;
                    $student->parent_firstname      = $value->parent_firstname;
                    $student->parent_lastname       = $value->parent_lastname;
                    $student->parent_address        = $value->parent_address;
                    $student->parent_telephone      = $value->parent_telephone;
                    $student->parent_email          = $value->parent_email;
                    $student->parent_occupation     = $value->parent_occupation;
                    $student->created_at            = date('Y-m-d');
                    if($student->save()){
                        $totalInsertion ++;
                        //Remove registered student from Temp student list
                        $savedStudent =  TempStudent::find($value->studentID);
                        $savedStudent->delete();
                        //Send Email
                    }
                }//end if -check Student Reg. no
            }//end foreach
            if($totalInsertion > 0){
                return back()->with('message', $totalInsertion .' students were registered and added to student standard list successfully. Please visit student list for additional student documentation.');
            }else{
                return back()->with('error', 'Sorry, registration failed. No student was registered. Please check Student Registration No. and try again.');
            }
        }//end if

     }


    //Print Admission Letter
    public function printAdmissionLetter($studentID)
    {
        if(Student::find($studentID))
        {
            $data['student'] = $this->pickOneStudent($studentID);
            //
            $feeSetupStudent = $this->getClassFeesSetupEnquiry($data['student']->student_class, ($this->getSession() ? $this->getSession()->termID : null), ($this->getSession() ? $this->getSession()->session_code : null));
            $data['classNameValue']                 = $feeSetupStudent['className'];
            $data['classID']                        = $feeSetupStudent['classNameValue'];
            $data['schoolTermValue']                = $feeSetupStudent['schoolTermValue'];
            $data['schoolSession']                  = $feeSetupStudent['schoolSession'];
            $data['allFeesSetup']                   = $feeSetupStudent['allFeesSetup'];
            $data['termName']                       = $feeSetupStudent['termName'];
            $data['getAllAssignedFees']             = $feeSetupStudent['getAllAssignedFees'];
            $data['getAllAssignedCoreFees']         = $feeSetupStudent['getAllAssignedCoreFees'];
            //
            $data['path'] = $this->studentImagePath();
        }else{
            return redirect()->back()->with('error', 'We are sorry, we cannot print this student admission letter now! There are some missing information. Try again later.');
        }

        //
       return view('student.admissionLetter', $data);
    }



    //Student Registration Number
    private function getStudentRegNo($classID)
    {
        $studentRegID = null;
        $getRegFormatName = $this->schoolProfile();
        $lastID = Student::orderBy('studentID', 'Desc')->value('studentID');
        $rollNo = ((StudentClass::find($classID)) ? (Student::where('deleted', 0)->where('active', 1)->where('student_class', $classID)->count()) : $this->get_rand_numbers(2));
        //Student Registration Number
        if(strlen($lastID) < 2){
            $newID = '000' . ($lastID + 1);
        }elseif(strlen($lastID) < 3){
            $newID = '00' . ($lastID + 1);
        }elseif(strlen($lastID) < 4){
            $newID = '0' . ($lastID + 1);
        }elseif(strlen($lastID) >= 4){
            $newID = ($lastID + 1);
        }else{
            $newID = '0001';
        }
        //Roll NUmber
        if(strlen($rollNo) < 2){
            $newRollNo = '00' . ($rollNo + 1);
        }elseif(strlen($rollNo) < 3){
            $newRollNo = '0' . ($rollNo + 1);
        }elseif(strlen($rollNo) >= 4){
            $newRollNo = ($rollNo + 1);
        }else{
            $newRollNo = '001';
        }
        //
        switch($getRegFormatName->student_regID_format)
        {
            case 1:
            $studentRegID =  $getRegFormatName->school_short_name.'/'.date('Y').'/'.$newID;
            break;

            case 2:
            $studentRegID =  $getRegFormatName->school_short_name.'/'.$newID.'/'.date('Y');
            break;

            case 3:
            $studentRegID =  $newID.'/'.date('Y').'/'.$getRegFormatName->school_short_name;
            break;

            case 4:
            $studentRegID =  $getRegFormatName->school_short_name.'-'.date('Y').'-'.$newID;
            break;

            case 5:
            $studentRegID =  $getRegFormatName->school_short_name.'-'.$newID.'-'.date('Y');
            break;

            case 6:
            $studentRegID =  $newID.'-'.date('Y').'-'.$getRegFormatName->school_short_name;
            break;

            case 7:
            $studentRegID =  $getRegFormatName->school_short_name.date('Y').$newID;
            break;

            case 8:
            $studentRegID =  $getRegFormatName->school_short_name.$newID.date('Y');
            break;

            case 9:
            $studentRegID =  $newID.date('Y').$getRegFormatName->school_short_name;
            break;

            case 10:
            $studentRegID =  $getRegFormatName->school_short_name.'/'.date('Y').$newID;
            break;

            case 11:
            $studentRegID =  $getRegFormatName->school_short_name.'/'.$newID.date('Y');
            break;

            case 12:
            $studentRegID =  $getRegFormatName->school_short_name.'/'.date('Y').'-'.$newID;
            break;

            case 13:
            $studentRegID =  $getRegFormatName->school_short_name.'/'.$newID.'-'.date('Y');
            break;

            case 14:
            $studentRegID =  $getRegFormatName->school_short_name.date('Y').'/'.$newID;
            break;

            case 15:
            $studentRegID =  $getRegFormatName->school_short_name.$newID.'/'.date('Y');
            break;
        }

        $data['roll']           = $newRollNo;
        $data['studentRegID']   = $studentRegID;

       return $data;
    }//

    //View Graduate/Withdraw List
    //View all students
    public function graduateWithdrawalList()
    {
        $classID = Session::get('classID');
        $studentID = Session::get('studentID');
        //
        $data['allClasses'] = $this->getClass();
        $data['allGraduateWithdrawStudentList'] = $this->listAllGraduateWithdrawalStudentInSchool($classID, $studentID);
        $data['allExtraCurricular'] = $this->getExtraCurricular();
        //
        return view('student.graduateWithdrawList', $data);
    }



}//end class
