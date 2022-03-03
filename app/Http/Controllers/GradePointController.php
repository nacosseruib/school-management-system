<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\GradePoint;
use Auth;
use Schema;
use Session;
use DB;

class GradePointController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    //create
    public function createGradePoint()
    {
        $data['allGradePoint'] = $this->getAllGradePoint();
        $data['allGradeRemark'] = $this->getGradeRemark();
        //Get Edit Data
        (Session::get('grade') ? $data['grade'] = Session::get('grade') : '');

        return view('setup.gradePoint', $data);
    }


    public function saveGradePoint(Request $request)
    {
        $this->validate($request, [
            'gradeFor'         => 'required|numeric|min:0|max:100', 
            'markFrom'         => 'required|numeric|between:0,999.99|min:0|max:100', 
            'markTo'           => 'required|numeric|between:0,999.99|min:0|max:100', 
            'makeGradeActive'  => 'required|numeric|max:100', 
            'gradePoint'        => 'required|string|max:10', //|unique:grade_point,grade_point_remark', 
            'gradeRemark'           => 'required|string|max:100',
            'classTeacherComment'   => 'required|string|max:190',
            'principalComment'     => 'required|string|max:190', 
        ]);

        $gradeID = trim($request->gradeID);
        if(GradePoint::find($gradeID)){
            $gradePoint = GradePoint::find($gradeID);
            $gradePoint->grade_for          = trim($request->gradeFor);
            $gradePoint->mark_from          = trim($request->markFrom);
            $gradePoint->mark_to            = trim($request->markTo);
            $gradePoint->active             = trim($request->makeGradeActive);
            $gradePoint->grade_point_remark = trim($request->gradePoint);
            $gradePoint->grade_remark       = trim($request->gradeRemark);
            $gradePoint->class_teacher_comment = trim($request->classTeacherComment);
            $gradePoint->principal_comment     = trim($request->principalComment);
            $gradePoint->updated_at         = date('Y-m-d H:i:s');
            if($gradePoint->save()){
                Session::forget('grade');
                return redirect()->route('createGradePoint')->with('message', 'Your record was updated successfully');
            }
        }else{
            $gradePoint = new GradePoint;
            $gradePoint->grade_for          = trim($request->gradeFor);
            $gradePoint->mark_from          = trim($request->markFrom);
            $gradePoint->mark_to            = trim($request->markTo);
            $gradePoint->active             = trim($request->makeGradeActive);
            $gradePoint->grade_point_remark = trim($request->gradePoint);
            $gradePoint->grade_remark       = trim($request->gradeRemark);
            $gradePoint->class_teacher_comment = trim($request->classTeacherComment);
            $gradePoint->principal_comment     = trim($request->principalComment);
            $gradePoint->created_at         = date('Y-m-d H:i:s');
            $gradePoint->updated_at         = date('Y-m-d H:i:s');
            if($gradePoint->save()){
                Session::forget('grade');
                return redirect()->route('createGradePoint')->with('message', 'New grade point was added successfully');
            }
        }
        //
        return redirect()->route('createGradePoint')->with('error', 'Sorry, we are having error while adding your grade record! Please, try again.');

    }
    
    //
    public function removeGradePoint($ID)
    {
        $success = 0;
        if(GradePoint::find($ID)){
            $success = GradePoint::where('gradeID', $ID)->delete();
        }
        if($success){
            return redirect()->route('createGradePoint')->with('message', 'A grade was successfully deleted.');
        }
        return redirect()->route('createGradePoint')->with('error', 'Sorry, we cannot delete this grade. Try again.');
        
    }

    // show edit data
    public function editGrade($ID)
    {
        if(GradePoint::find($ID)){
            Session::put('grade', GradePoint::find($ID));
        }else{
            Session::forget('grade');
        }
        return redirect()->route('createGradePoint');
        
    }

    // cancel edit
    public function cancelEditGrade()
    {
        Session::forget('grade');

        return redirect()->route('createGradePoint')->with('message', 'Edit was canceled. You can now add new record.');
    }


}
