<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mark;
use App\Models\Student;
use App\User;
use App\Models\ResultPin;
use Captcha;
use Auth;
use Session;

class HomeController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function home()
    {
        Session::forget('newFoundStudent');
        $data['allClass'] = $this->getClass();
        $data['allSubject'] = $this->getSubject();
        $data['allScoreType'] = $this->getScoreType();
        $data['totalStudent'] = Student::where('active', 1)->where('deleted', 0)->count();
        $data['totalTeacher'] = User::where('suspend', 0)->where('deleted', 0)->where('id', '<>', 1)->count();
        $data['totalResultViewer'] = ResultPin::sum('user_no_of_time_use');
        $data['totalResultComputed'] = Mark::where('active', 1)->where('publish', 1)->count();
        
        return view('home.home', $data);
    }

}
