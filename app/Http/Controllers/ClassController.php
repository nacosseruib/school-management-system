<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentClass;
use App\Exports\ExportClass;
use App\Models\Mark;
use Excel;
use Auth;
use Schema;
use Session;
use DB;

class ClassController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function createClass()
    {
        Session::forget('classIDSet');
        Session::forget('classNameSet');
        $data['allclass'] = $this->getAllClass();
        //Get Edit Data
        (Session::get('class') ? $data['class'] = Session::get('class') : '');
        
        return view('setup.class', $data);
    }

    
    //Print
    public function printClass()
    {
        Session::forget('classIDSet');
        Session::forget('classNameSet');
        $data['allclass'] = $this->getClass();
        //
        return view('setup.printClass', $data);
    }

    //Export Class - Download
    public function exportClass($type)
    {
       return Excel::download(new ExportClass, date('d-m-Y-') . time() .'-List-of-Class.' . $type);
    }
    
    //
    public function storeClass(Request $request)
    {
        $this->validate($request, [
            'classCode'   => 'required|alpha_num|max:255', 
            'className'   => 'required|string|max:255', 
            'classStatus' => 'required|numeric|max:3',
        ]);
        
        $classID = trim($request->classID);
        $success = 0;
        $message = "Sorry, we cannot add new class! Try again.";
        if(StudentClass::find($classID))
        {
            $class = StudentClass::find($classID);
            $class->class_code  = $request->classCode;
            $class->class_name  = $request->className;
            $class->description = $request->description;
            $class->active      = $request->classStatus;
            $class->created_at  = date('Y-m-d');
            $success = $class->save();
            $message = "Your class details was updated successfully.";
            Session::forget('class');
        }else{
            $this->validate($request, [
                'classCode'   => 'required|alpha_num|max:255|unique:student_class,class_code', 
                'className'   => 'required|string|max:255|unique:student_class,class_name', 
            ]);
            //Insert New
            $class = new StudentClass;
            $class->class_code  = $request->classCode;
            $class->class_name  = $request->className;
            $class->description = $request->description;
            $class->active      = $request->classStatus;
            $class->created_at  = date('Y-m-d');
            $success = $class->save();
            $message = "New class was added successfully.";
            Session::forget('class');
        }
        if($success){
            return redirect()->route('createClass')->with('message', $message);
        }
        return redirect()->route('createClass')->with('error', $message);
        
    }

    
    public function removeClass($ID)
    {
        $class = new StudentClass;
        $success = 0;
        if($class::where('classID', $ID)->first()){
             if(!Mark::where('classID', $ID)->first() and !Student::where('student_class', $ID)->first())
            { 
                $success = $class::where('classID', $ID)->delete();
            }else{
                $success = $class::where('classID', $ID)->update(['active'=>0]);
            }
        }
        if($success){
            return redirect()->route('createClass')->with('message', 'A class was deleted/Disabled successfully.');
        }
        return redirect()->route('createClass')->with('error', 'Sorry, we cannot delete this class, is in use.');
    }


    // show edit data
    public function editClass($ID)
    {
        if(StudentClass::find($ID)){
            Session::put('class', StudentClass::find($ID));
        }else{
            Session::forget('class');
        }
        return redirect()->route('createClass')->with('info', 'You can edit your record now.');
        
    }

    // cancel edit
    public function cancelEditClass()
    {
        Session::forget('class');

        return redirect()->route('createClass')->with('message', 'Edit was canceled. You can now add new record.');
    }


    //get class by JSON
    public function getClassDetailJson($ID)
    {
        $success['className'] = 'Sorry, we cannot get the class name.';
        Session::forget('classIDSet');
        Session::forget('classNameSet');
        if(StudentClass::find($ID)){
            $result = StudentClass::find($ID);
            Session::put('classIDSet', $result->classID);
            Session::put('classNameSet', $result->class_name);
            $success['className'] = $result->class_name;
            $success['subject'] = $this->getStudentSubject($ID);
        }
        return $success;
    }


}
