<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentSubject;
use App\Models\ScoreType;
use App\Models\PublicSession;
use App\Models\SchoolProfile;
use App\Exports\ExportMark;
use App\Imports\ImportMark;
use App\Models\TempMark; 
use App\Models\Mark;
use App\Models\Term;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class ComputeResultController extends ViewResultFunctionController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function create()
    {   
       
        $data['allClass']       = $this->getClass();
        $data['allSubject']     = $this->getSubject();
        $data['allScoreType']   = $this->getScoreType();
        $data['allTerm']        = $this->getTerm();
        $data['getSession']     = $this->getSession();
        $data['studentPath']    = $this->studentImagePath();
        if(empty(Session::get('newFoundStudent')))
        {
            $getReturn = $this->searchStudentInClass(Session::get('scoretypeID'), Session::get('classIDSet'), Session::get('subjectIDSet'), Session::get('termIDSet'), ($this->getSession() ? $this->getSession()->session_code : ''));
            $data['foundStudent'] = $getReturn['foundStudent'];
            $data['markTest1'] = $getReturn['markTest1'];
            $data['markTest2'] = $getReturn['markTest2'];
            $data['markExam'] = $getReturn['markExam'];
        }else{
            $getReturn =  Session::get('newFoundStudent');
            $data['foundStudent'] =  $getReturn['foundStudent'];
            $data['markTest1'] = $getReturn['markTest1'];
            $data['markTest2'] = $getReturn['markTest2'];
            $data['markExam'] = $getReturn['markExam'];
        } 
        //
        if(Session::get('scoretypeID') == '1'){
            Session::put('maxScore', StudentSubject::where('subjectID', Session::get('subjectIDSet') )->value('max_ca1'));
        }else if(Session::get('scoretypeID') == '2'){
            Session::put('maxScore', StudentSubject::where('subjectID', Session::get('subjectIDSet') )->value('max_ca2'));
        }else if(Session::get('scoretypeID') == '3'){
            Session::put('maxScore', StudentSubject::where('subjectID', Session::get('subjectIDSet') )->value('max_exam'));
        }else{
            Session::put('maxScore', '100');
        }

        return view('computeResult.home', $data);
    }
    
    //
    public function findStudentInClass(Request $request)
    {   
        $this->validate($request, [
            'className'     => 'required|alpha_num|max:255', 
            'subjectName'   => 'required|alpha_num|max:255', 
            'scoreTypeName' => 'required|alpha_num|max:255',
        ]);
        //Unset Session to be used
        Session::forget('classIDSet');
        Session::forget('classNameSet');
        Session::forget('subjectIDSet');
        Session::forget('subjectNameSet');
        Session::forget('scoretypeID');
        Session::forget('setScoreTypeName');
        Session::forget('newFoundStudent'); 
        Session::forget('maxScore'); 
        //Set New Session
        $class = new StudentClass;
        $scoreType = new ScoreType;
        $subject = new StudentSubject;
        Session::put('termIDSet', $request->termName);
        Session::put('termNameSet', $this->getTermName(Session::get('termIDSet')) );
        Session::put('classIDSet', $request->className);
        Session::put('subjectIDSet', $request->subjectName);
        Session::put('scoretypeID', $request->scoreTypeName);
        Session::put('classNameSet', $class::where('classID', $request->className)->value('class_name'));
        Session::put('subjectNameSet', $subject::where('subjectID', $request->subjectName)->value('subject_name'));
        Session::put('setScoreTypeName', $scoreType::where('scoretypeID', $request->scoreTypeName)->value('score_type'));
        //Get all student found 
        Session::put('newFoundStudent', $this->searchStudentInClass($request->scoreTypeName, $request->className, $request->subjectName, $request->termName, ($this->getSession() ? $this->getSession()->session_code : '') ));

        return redirect()->route('createMark');
    }

    //
    public function setScoreType($ID)
    {
        $scoreType = new ScoreType;
        Session::forget('setScoreTypeCode');
        Session::forget('setScoreTypeName');
        if(DB::table('score_type')->where('scoretypeID', $ID)->first()){
            $result = $scoreType::where('scoretypeID', $ID)->first();
            Session::put('scoretypeID', $result->scoretypeID);
            Session::put('setScoreTypeName', $result->score_type);
        }
        return;
    }



    //Submit Score
    public function saveStudentScore(Request $request)
    { 
        
        $this->validate($request, [
            'className'         => 'required|numeric', 
            'subjectName'       => 'required|numeric', 
            'scoreType'         => 'required|numeric', 
            'term'              => 'required|numeric', //NB.:Not use for now (but vital)
            'score'             => 'required|array',
            'score.*'           => 'required|numeric|between:0,100.99|min:0|max:100',
            'studentIdSelected' => 'required|array',
        ]);
        $score          = ($request->score);
        $studentID      = ($request->studentIdSelected);
        $classID        = trim($request->className);
        $subjectID      = trim($request->subjectName);
        $scoreTypeID    = ($request->scoreType);;
        $sessionCode    = trim($this->getSession()->session_code);
        $termID         = ($request->term); //trim($this->getSession()->termID);
        
        //$schoolDetails = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->value('update_pulish_result');
        $currentSessionAllowComputeResult = ($this->getSession() ? $this->getSession()->allow_result_computation : 0);
        
        if($this->getSession()){
            if($this->getSession()->allow_result_computation == 0){
                return redirect()->route('createMark')->with('error', 'Sorry, you cannot compute any result for this Term/Session! Please, contact your administrator. Thanks');
            }
        }
        //get score as an array
		foreach ($score as $mark) {
            if(($mark > -1) and ($mark <> 'NO-SCORE')){
                $arrayMark[] = $mark;
            }else{
                //Do nothing 
            }
        }
        $editLimit  = 0;
        $actionPerform = null;
        $totalStudentComputed = 0;
        $totalStudentAlreadyComputed = 0;
        $mark = New Mark;
        foreach($studentID as $key=>$stdID)
		{	
            $update = Mark::where('studentID', $stdID)
                //->where('scoretypeID', $scoreTypeID)
                ->where('classID', $classID)
                ->where('subjectID', $subjectID)
                ->where('session_code', $sessionCode)
                ->where('termID', $termID)
                ->first();
            if(!empty($update))//update
            {
                $editLimit = $update->edit_limit;
                $actionPerform = "updated";
                if(($scoreTypeID == 1) and ($update->edit <= $update->edit_limit) and ($currentSessionAllowComputeResult == 1)) //($update->publish < 1))//&& ($update->edit < $update->edit_limit) or ($update->publish < 1)
                {
                    Mark::where('studentID', $stdID)
                        ->where('classID', $classID)
                        ->where('subjectID', $subjectID)
                        ->where('session_code', $sessionCode)
                        ->where('termID', $termID)->update([
                            'test1'      => str_replace(',', '', (number_format($arrayMark[$key], 1))),
                            'edit'       => ($update->edit + 1),
                            'computed_by_ID' => Auth::User()->id,
                            'updated_at' => date('Y-m-d H:i:s-A')
                        ]);
                    $totalStudentComputed ++;
                }else if(($scoreTypeID == 2) and ($update->edit <= $update->edit_limit) and ($currentSessionAllowComputeResult == 1)) // ($update->publish < 1)) //&& ($update->edit < $update->edit_limit) or ($update->publish < 1)
                {
                    Mark::where('studentID', $stdID)
                        ->where('classID', $classID)
                        ->where('subjectID', $subjectID)
                        ->where('session_code', $sessionCode)
                        ->where('termID', $termID)->update([
                            'test2'      => str_replace(',', '', (number_format($arrayMark[$key], 1))),
                            'edit'       => ($update->edit + 1),
                            'computed_by_ID' => Auth::User()->id,
                            'updated_at' => date('Y-m-d H:i:s-A')
                        ]);
                    $totalStudentComputed ++;
                }else if(($scoreTypeID == 3) and ($update->edit <= $update->edit_limit) and ($currentSessionAllowComputeResult == 1)) //($update->publish < 1)){ //&& ($update->edit < $update->edit_limit) or ($update->publish < 1)
                {
                    Mark::where('studentID', $stdID)
                        ->where('classID', $classID)
                        ->where('subjectID', $subjectID)
                        ->where('session_code', $sessionCode)
                        ->where('termID', $termID)->update([
                            'exam'       => str_replace(',', '', (number_format($arrayMark[$key], 1))),
                            'edit'       => ($update->edit + 1),
                            'computed_by_ID' => Auth::User()->id,
                            'updated_at' => date('Y-m-d H:i:s-A')
                        ]);
                    $totalStudentComputed ++;
                }else{
                    $totalStudentAlreadyComputed ++;
                }
            }else{//insert
                $actionPerform      = "submitted";
                $mark = New Mark;
                $mark->studentID    = $stdID;
                $mark->classID      = $classID;
                $mark->subjectID    = $subjectID;
                $mark->scoretypeID  = $scoreTypeID;
                $mark->termID       = $termID;
                $mark->created_at   = date('Y-m-d H:i:s-A');
                $mark->updated_at   = date('Y-m-d H:i:s-A');
                $mark->computed_by_ID = Auth::User()->id;
                $mark->session_code = $sessionCode; 
                if($scoreTypeID == 1)
                {
                    $mark->test1      = str_replace(',', '', (number_format($arrayMark[$key], 1)));
                }else if( $scoreTypeID == 2){
                    $mark->test2      = str_replace(',', '', (number_format($arrayMark[$key], 1)));
                }else if($scoreTypeID == 3){
                    $mark->exam       = str_replace(',', '', (number_format($arrayMark[$key], 1)));
                }else{
                    $mark->test1      = str_replace(',', '', (number_format($arrayMark[$key], 1)));
                }
                if($mark->save()){
                    $totalStudentComputed ++;
                }else{
                    $totalStudentAlreadyComputed ++;
                }
            }
            $key ++;
		}//end foreach
        if($studentID){
            Session::forget('newFoundStudent');
            Session::put('newFoundStudent', $this->searchStudentInClass($scoreTypeID, $classID, $subjectID, $termID, ($this->getSession() ? $this->getSession()->session_code : '')));
            return redirect()->route('createMark')->with("message", "Your scores were $actionPerform successfully <br /> NOTE: <br /> " . $totalStudentComputed ." Student(s') results were submitted. <br /> ". $totalStudentAlreadyComputed." Student(s') results not submitted. May be those results have been modified more than ".$editLimit. " time(s) or published");
        }else{
            return redirect()->route('createMark')->with('error', 'Sorry, will cannot submit your score! It seems this result(s) has been computed before. Please, try again.');
        }
    }


    //Post mark sheet
    public function postMarkSheet(Request $request)
    {
        $this->validate($request, [
            'className'     => 'required|alpha_num|max:255', 
            'subjectName'   => 'required|alpha_num|max:255', 
        ]);
        $termID = $request['termName'];
        $classID = $request['className'];
        $subjectID = $request['subjectName'];
        $scoreType = null;
        $getReturn = $this->searchStudentInClass($scoreType, $classID, $subjectID, $termID, ($this->getSession() ? $this->getSession()->session_code : ''));
        Session::forget('getMarkData');
        Session::put('getMarkData', $getReturn);
        Session::put('classIDSet', $request->className);
        Session::put('subjectIDSet', $request->subjectName);
        Session::put('classNameSet', StudentClass::where('classID', $request->className)->value('class_name'));
        Session::put('subjectNameSet', StudentSubject::where('subjectID', $request->subjectName)->value('subject_name'));
        //Get Max Score For Subject (Header)
        Session::put('maxSubjectScore', StudentSubject::where('subjectID', $request->subjectName)->first());

        return redirect()->route('viewMarkSheet');
    }


    //View mark sheet
    public function createMarkSheet()
    {
        $data['allClass']       = $this->getClass();
        $data['allSubject']     = $this->getSubject();
        $data['allTerm']        = $this->getTerm();
        $data['getSession']     = $this->getSession();
        $getReturn              = Session::get('getMarkData');
        if(!empty($getReturn)){
            $data['foundStudent']   = $getReturn['foundStudent'];
            $data['markTest1']      = $getReturn['markTest1'];
            $data['markTest2']      = $getReturn['markTest2'];
            $data['markExam']       = $getReturn['markExam'];
            $data['markTotal']      = $getReturn['markTotal'];
            $data['markGrade']      = $getReturn['markGrade'];
            $data['markRemark']     = $getReturn['markRemark'];
            $data['markPercentage'] = $getReturn['markPercentage'];
            $data['computedBy']     = $getReturn['computedBy'];
            $data['dateComputed']   = $getReturn['dateComputed'];
            $data['markID']         = $getReturn['getMarkID'];
            
        }else{
            $data['foundStudent']   = array();
            $data['markTest1']      = null;
            $data['markTest2']      = null;
            $data['markExam']       = null;
            $data['markTotal']      = null;
            $data['markGrade']      = null;
            $data['markRemark']     = null;
            $data['markPercentage'] = null;
            $data['computedBy']     = null;
            $data['dateComputed']   = null;
            $data['markID']         = null;
        }
    
        return view('computeResult.viewMarkSheet', $data);
    }



    //View Student Report Card : Post parameters
    public function postStudentReportSheetParameters(Request $request)
    {
        Session::forget('classIDResult');
        Session::forget('studentIDResult');
        Session::forget('classNameSetResult');
        Session::forget('termNameResult'); 
        Session::forget('termIDResult');
        Session::forget('sessionCodeResult');
        Session::forget('typeOfResultToPreview');
        Session::forget('publicSessionID'); 
        Session::forget('StudentNameSetResult');
        Session::forget('getStudentDetails');
        Session::forget('getResultData');

        $this->validate($request, [
            'schoolSession'      => 'required|string|max:255', 
            'schoolTerm'      => 'required|alpha_num|max:255', 
            'className'     => 'required|alpha_num|max:255', 
            'studentName'   => 'required|alpha_num|max:255',
        ]);
        $classID    = $request['className'];
        $studentID  = $request['studentName'];
        $termID     = $request['schoolTerm'];
        $publicSessionID = $request['schoolSession'];
        $this->postStudentReportSheetParametersResult($studentID, $classID, $termID, $publicSessionID);
        //
        return redirect()->route('viewStudentReportSheet');
    }


    //Preview Result result
    public function viewStudentReportSheet()
    {   
        $passData['getClassNameFromMark'] = $this->getAllClassFromMark();
        $data = $this->viewStudentReportSheetResult();
        //
        return view('report.reportCardSheet.reportTemplate', $data, $passData);
    }



    //EXCEL IMPORT AND DOWNLOAD
    public function createMarkImport()
    {
        $data['tempMarkExcel'] = $this->viewImportedExcelData();
        return view('computeResult.importMarkExcel', $data);
    }

    //Download
    public function downloadMarkExcel($type)
    {
       return Excel::download(new ExportMark, date('d-m-Y') .'-Imported-Marks-Excel-File.' . $type);
    }

    //Download New
    public function downloadNewMarkExcel($type)
    {
        return Excel::download(new TempMark, 'Blank-Excel-File.' . $type);
    }

    //Import Mark from Excel
    public function importMarkFromExcel(Request $request)
    {
        $this->validate($request, [
            'importStudentMark' => 'required', 
        ]);
        TempMark::where('computed_by_ID', Auth::User()->id)->truncate();
        $path = $request->file('importStudentMark');
        Excel::import(new ImportMark, $path);
        
        return back()->with('message', 'Marks were extracted successfully from file.');
    }

     //fetch Imported Data
     public function viewImportedExcelData()
     {
         return TempMark::where('computed_by_ID', Auth::User()->id)->get();
     }


    //Delete mark from Temp-Mark
    public function deleteMarkFromTempMark($getID)
    {
        
        if(($getID <> null) and TempMark::find($getID))
        {
            $mark = TempMark::find($getID);
            $mark->delete();
            //
            return back()->with('message', 'A record was removed from the list.');
        }else{
            //
            return back()->with('error', 'Sorry we cannot remove this record from our system! Please try again.');
        }
        

    }

     //submit Mark to score list
     public function submitMarkScoreList()
     {
        $tempMark = $this->viewImportedExcelData();
        $editLimit = 0;
        $totalInsertion = 0;
        $totalStudentComputed = 0;
        $totalStudentAlreadyComputed = 0;
        if($tempMark)
        {  
            foreach($tempMark as $value)
            {   
                $update = Mark::where('studentID', $value->studentID)
                        ->where('classID', $value->classID)
                        ->where('subjectID', $value->subjectID)
                        ->where('session_code', $value->session_code)
                        ->where('termID', $value->termID)
                        ->select('*', 'publish', 'markID')
                        ->first();
                if($update)
                { 
                    if($update->publish == 1)
                    {
                        if(Student::where('studentID', $value->studentID)->first() and  $value->classID)
                        {
                            $mark = New Mark;
                            $mark->studentID    = $value->studentID;
                            $mark->classID      = $value->classID;
                            $mark->subjectID    = $value->subjectID;
                            $mark->scoretypeID  = 3;
                            $mark->termID       = $value->termID;
                            $mark->created_at   = date('Y-m-d H:i:s-A');
                            $mark->updated_at   = date('Y-m-d H:i:s-A');
                            $mark->computed_by_ID = Auth::User()->id;
                            $mark->session_code = $value->session_code; 
                            $mark->test1 = $value->test1; 
                            $mark->test2 = $value->test2; 
                            $mark->exam = $value->exam; 
                            if($mark->save()){
                                //delete record submitted
                                $getDelete = TempMark::find($value->markID);
                                ($getDelete ? $getDelete->delete() : '');
                                
                                $totalStudentComputed ++;
                            }else{
                                $totalStudentAlreadyComputed ++;
                            }
                        }
                    }else{
                        if(Student::where('studentID', $value->studentID)->first() and  $value->classID)
                        {
                            $mark = Mark::find($update->markID);
                            $mark->studentID    = $value->studentID;
                            $mark->classID      = $value->classID;
                            $mark->subjectID    = $value->subjectID;
                            $mark->scoretypeID  = 3;
                            $mark->termID       = $value->termID;
                            $mark->created_at   = date('Y-m-d H:i:s-A');
                            $mark->updated_at   = date('Y-m-d H:i:s-A');
                            $mark->computed_by_ID = Auth::User()->id;
                            $mark->session_code = $value->session_code; 
                            $mark->test1 = $value->test1; 
                            $mark->test2 = $value->test2; 
                            $mark->exam = $value->exam; 
                            if($mark->save()){
                                //delete record submitted
                                $getDelete = TempMark::find($value->markID);
                                ($getDelete ? $getDelete->delete() : '');

                                $totalStudentComputed ++;
                            }else{
                                $totalStudentAlreadyComputed ++;
                            }
                        }
                    }
                }else{
                    if(Student::where('studentID', $value->studentID)->first() and  $value->classID)
                    {
                            $mark = New Mark;
                            $mark->studentID    = $value->studentID;
                            $mark->classID      = $value->classID;
                            $mark->subjectID    = $value->subjectID;
                            $mark->scoretypeID  = 3;
                            $mark->termID       = $value->termID;
                            $mark->created_at   = date('Y-m-d H:i:s-A');
                            $mark->updated_at   = date('Y-m-d H:i:s-A');
                            $mark->computed_by_ID = Auth::User()->id;
                            $mark->session_code = $value->session_code; 
                            $mark->test1 = $value->test1; 
                            $mark->test2 = $value->test2; 
                            $mark->exam = $value->exam; 
                            if($mark->save()){
                                //delete record submitted
                                $getDelete = TempMark::find($value->markID);
                                ($getDelete ? $getDelete->delete() : '');
                                
                                $totalStudentComputed ++;
                            }else{
                                $totalStudentAlreadyComputed ++;
                            }
                    }//if
                }//if
            }//end foreach

            if($totalStudentComputed){
                return back()->with("message", "Your scores were submitted successfully <br /> NOTE: <br /> " . $totalStudentComputed ." Student(s') results were submitted. <br /> ". $totalStudentAlreadyComputed." Student(s') results not submitted. May be the student details do not conformed with the details on our system or those scores have been modified more than the limit time or scores have been published");
            }else{
                return back()->with('error', 'Sorry, will cannot submit your score! It seems the score details are not accurate. Please, try again.');
            }
        }else{
            return back()->with('error', 'Sorry, will cannot submit your score! It seems the score details are not accurate. Please, try again.');   
        }//end if
        
     }
 

    //SINGLE DELETION:  Delete score/mark from Mark
    public function deleteScoreMarkFromMark($getID)
    {
        if(($getID <> null) and Mark::where('markID', $getID)->first())
        {
            $mark = Mark::where('markID', $getID)->delete(); //->where('publish', 0)
            //
            return back()->with('message', 'The student selected score/Mark was deleted successfully.');
        }else{
            //
            return back()->with('error', 'Sorry we cannot delete this score/mark from our system! The score/mark is in used. Please try again.');
        }
    }//
    
    
    //MULTIPLE DELETION:  Delete score/mark from Mark
    public function deleteSelectedScoreMarkFromMark(Request $request)
    {
         $this->validate($request, [
            'selectedStudentCheckbox'   => 'required|array', 
        ]);
        $getSelectedArray = $request['selectedStudentCheckbox'];
        $counter = 0;
        foreach($getSelectedArray as $getID){
            if(($getID <> null) and Mark::where('markID', $getID)->first())
            {
                $mark = Mark::where('markID', $getID)->delete(); //->where('publish', 0)
                $counter ++;
            }else{
                //
            }
            
        }
        if($counter)
        {
            return back()->with('message', 'The student selected student score/Mark(s) were deleted successfully. <br /> Total Deletion: '. $counter);
        }else{
            return back()->with('error', 'Sorry we cannot delete this score/mark from our system! The score/mark is in used. Please try again.');
        }
    }//


    

}//end class
