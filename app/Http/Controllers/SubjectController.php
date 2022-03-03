<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentClass;
use App\Models\StudentSubject;
use App\Exports\ExportSubject;
use App\Models\Mark;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class SubjectController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function createSubject()
    {
        Session::forget('subjectIDSet');
        Session::forget('subjectNameSet');
        $data['allClass'] = $this->getClass();
        $data['allSubject'] = $this->getAllSubject();
        //Get Edit Data
        (Session::get('subject') ? $data['subject'] = Session::get('subject') : '');
        
        return view('setup.subject', $data);
    }

    
    //Print
    public function printSubject()
    {
        Session::forget('subjectIDSet');
        Session::forget('subjectNameSet');
        $data['allClass'] = $this->getClass();
        $data['allSubject'] = $this->getSubject();
        //
        return view('setup.printSubject', $data);
    }

    //Export Class - Download
    public function exportSubject($type)
    {
       return Excel::download(new ExportSubject, date('d-m-Y-').time() .'-List-of-Subject.' . $type);
    }
    
    
    //
    public function storeSubject(Request $request)
    {   
        $this->validate($request, [
            'className'     => 'required|numeric',
            'maximumTest1'  => 'required|between:0,20.99|min:0|max:100',
            'maximumTest2'  => 'required|between:0,20.99|min:0|max:100',
            'maximumExam'   => 'required|between:0,100.99|min:0|max:100',
            'subjectStatus' => 'required|string|max:3',
        ]); 
        $subjectID = trim($request->subjectID);
        $success = 0;
        $message = "Sorry, we cannot add new subject! Try again.";
        if(StudentSubject::find($subjectID))
        {
            $subject = StudentSubject::find($subjectID);
            $subject->subject_code  = $request->subjectCode;
            $subject->classID       = $request->className;
            $subject->subject_name  = $request->subjectName;
            $subject->subject_description = $request->description;
            $subject->max_ca1       = $request->maximumTest1;
            $subject->max_ca2       = $request->maximumTest2;
            $subject->max_exam      = $request->maximumExam;
            $subject->active        = $request->subjectStatus;
            $subject->created_at    = date('Y-m-d');
            $success = $subject->save();
            $message = 'Your record was updated successfully.';
            Session::forget('subject');
        }else{
            $this->validate($request, [
                'subjectCode'   => 'required|alpha_num|max:255|unique:student_subject,subject_code,null,subjectID,classID,' . $request->className,
                'subjectName'   => 'required|string|max:255|unique:student_subject,subject_name,null,subjectID,classID,' . $request->className,
            ]);
            $subject = new StudentSubject;
            $subject->subject_code  = $request->subjectCode;
            $subject->classID       = $request->className;
            $subject->subject_name  = $request->subjectName;
            $subject->subject_description = $request->description;
            $subject->max_ca1       = $request->maximumTest1;
            $subject->max_ca2       = $request->maximumTest2;
            $subject->max_exam      = $request->maximumExam;
            $subject->active        = $request->subjectStatus;
            $subject->created_at    = date('Y-m-d');
            $success  = $subject->save();
            $message = 'New subject was added successfully.';
            Session::forget('subject');
        }
        if($success){
            return redirect()->route('createSubject')->with('message', $message);
        }
        return redirect()->route('createSubject')->with('error', $message);
        
    }

    
    public function removeSubject($ID)
    {
        $subject = new StudentSubject;
        $success = 0;
        if($subject::where('subjectID', $ID)->first()){
            if(!Mark::where('subjectID', $ID)->first())
            { 
                $success = $subject::where('subjectID', $ID)->delete();
            }else{
                $success = $subject::where('subjectID', $ID)->update(['active'=>0]);
            }
        }
        if($success){
            return redirect()->route('createSubject')->with('message', 'A subject was deleted/Disabled successfully.');
        }
        return redirect()->route('createSubject')->with('error', 'Sorry, we cannot delete this subject, is in use.');
    }

    // show edit data
    public function editSubject($ID)
    {
        if(StudentSubject::find($ID)){
            $editSubject = StudentSubject::where('subjectID', $ID)
                ->join('student_class', 'student_class.classID', '=', 'student_subject.classID')
                ->select('*', 'student_subject.active as subjectActive')
                ->first();
            Session::put('subject', $editSubject);
        }else{
            Session::forget('subject');
        }
        return redirect()->route('createSubject')->with('info', 'You can edit your record now.');
        
    }

    // cancel edit
    public function cancelEditSubject()
    {
        Session::forget('subject');

        return redirect()->route('createSubject')->with('message', 'Edit was canceled. You can now add new record.');
    }


    //get subject by subject ID
    public function getSubjectDetailJson($ID)
    {
        $subject = new StudentSubject;
        $success = 'Sorry, we cannot get the subject name.';
        Session::forget('subjectIDSet');
        Session::forget('subjectNameSet');
        if($subject::find($ID)){
            $result = $subject::find($ID);
            Session::put('subjectIDSet', $result->subjectID);
            Session::put('subjectNameSet', $result->subject_name);
            $success = $result->subject_name;
        }
        return $success;
    }



}
