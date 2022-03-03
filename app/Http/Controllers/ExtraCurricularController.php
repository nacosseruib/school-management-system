<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\StudentExtraCurricular; 
use Auth;
use Schema;
use Session;
use DB;

class ExtraCurricularController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function createExtraCurricular()
    {
        $data['allExtraCurricular'] = $this->getExtraCurricular();
        
        return view('setup.extraCurricular', $data);
    }


    public function storeExtraCurricular(Request $request)
    {
        $this->validate($request, [
            'extraCurriculumName'         => 'required|string|max:50|unique:extra_curricular,curricular_name', 
            'extraCurriculumDescription'  => 'max:255', 
        ]);
        $extraCurricular = new StudentExtraCurricular;
        $extraCurricular->curricular_name         = $request->extraCurriculumName;
        $extraCurricular->curricular_description  = $request->extraCurriculumDescription;
        $extraCurricular->created_at  = date('Y-m-d');
        if($extraCurricular->save()){
            return redirect()->route('createExtra')->with('message', 'New extra curriculum was added successfully.');
        }
        return redirect()->route('createExtra')->with('error', 'Sorry, we cannot add new extra curriculum! Try again.');
        
    }

    
    public function removeExtraCurricular($ID = null)
    {
        $extraCurricular = new StudentExtraCurricular;
        $success = 0;
        if($extraCurricular::where('curricularID', $ID)->first()){
            $success = $extraCurricular::where('curricularID', $ID)->delete();
        }
        if($success){
            return redirect()->route('createExtra')->with('message', 'An extra curriculum was deleted successfully.');
        }
        return redirect()->route('createExtra')->with('error', 'Sorry, we cannot delete this extra curriculum , is in use.');
        
    }


}
