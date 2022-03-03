<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendEmailTeacher;
use App\Mail\sendEmailTeacherChangeAccount;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Teacher;
use App\Models\SchoolProfile;
use App\Models\Role;
use App\User;
use App\Exports\ExportBasicTeacher;
use App\Exports\ExportFullTeacher;
use Excel;
use PDF;
use Auth;
use File;
use Schema;
use Session;
use DB;

class TeacherController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function createTeacher()
    {
        $data['allClass'] = $this->getClass();
        $data['allUser'] = $this->getUser();
        $data['allRole'] = Role::orderBy('roleID', 'Asc')->where('role_active', 1)->where('roleID', '<>', 1)->get();

        //Get New Teacher Registration Number
        $data['newTeacherRegNo'] = $this->getTeacherRegNo(null);
        
        return view('teacher.createTeacher', $data);
    }

    
    //print Teacher
    public function printTeacher()
    {   
        $data['allUserList'] = $this->getUserList();
        //
        return view('teacher.printTeacher', $data);
    }

    //Export Teacher - Download
    public function exportBasicTeacher($type)
    {
       return Excel::download(new ExportBasicTeacher, date('d-m-Y-') . time() .'-Basic-Teacher-Details.' . $type);
    }

    //Export Teacher - Download
    public function exportFullTeacher($type)
    {
       return Excel::download(new ExportFullTeacher, date('d-m-Y-') . time() .'-Full-Teacher-Details.' . $type);
    }

    //Export Teacher - Download
    public function exportBasicTeacherPDF()
    {   
        $data['allUserList'] = $this->getAllActiveUser();
        $pdf  = PDF::loadView('teacher.printTeacherPDF', $data);
        //
        return $pdf->download(date('d-m-Y-') . time() .'-Basic-Teacher-Details.pdf');
    }


    public function storeTeacher(Request $request)
    {   
        $this->validate($request, [
            'surname'            => ['required', 'string', 'min:3', 'max:100'],
            'otherName'          => ['required', 'string', 'min:3', 'max:100'],
            'userRegistrationId' => ['required', 'string', 'max:100'],
            'admittedDate'       => ['required', 'date'],
            'gender'             => ['required', 'string'],
            'teacherClass'       => ['required'],
            'userType'           => ['required', 'numeric'],
            'designation'        => ['required', 'string'],
            'email'              => ['required', 'email', 'min:3', 'max:100', 'unique:users'],  
            'password'           => ['required', 'string', 'min:5', 'max:100', 'confirmed'],
        ]);
        $file       = $request->file('file');
        if(!empty($file) or $file != "")
        {
          $this->validate($request, [
            'file'          => 'required|image|mimes:png,jpg,jpe,jpeg|max: 3072', 
          ]);
        }
        //Create User
        $user = New User;
        $user->email        = $request->email;
        $user->password     = Hash::make(trim($request['password']));
        $user->name         = $request->surname;
        $user->other_name   = $request->otherName;
        $user->user_type    = $request->userType;
        $user->designation  = $request->designation;
        $user->gender       = $request->gender;
        $user->address      = $request->address;
        $user->telephone    = $request->phoneNumber;
        $user->userRegistrationId = $request->userRegistrationId;
        $user->admitted_date = $request->admittedDate;
        //send email parameters 
        $schoolDetails = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->value('school_full_name');
        $schoolName = ($schoolDetails ? $schoolDetails : 'School Eportal');
        $emailData = ([
            'name'=>$request['surname'] .' '. $request['otherName'], 
            'password'=>$request['password'], 
            'post'=>$request['designation'], 
            'staffID'=>$request['userRegistrationId'], 
            'email'=>$request['email'],
            'schoolName' => $schoolName,
            'loginUrl'=>'https://'. $_SERVER["HTTP_HOST"].'/login'
            ]);
        if($user->save())
        {
            //Send Email
            ($request['email'] ? Mail::to($request['email'])->send(new SendEmailTeacher($emailData)) : '');
            
            $getUserID = $user::orderBy('id', 'Desc')->value('id');
            if(!empty($request->teacherClass) and ($request->teacherClass > 0))
            {
                foreach($request->teacherClass as $convertToArray){ 
                    $arrayClass[] = $convertToArray;
                }
                
                foreach($arrayClass as $listClass){ 
                    if(!DB::table('teacher_class')->where('userID', $getUserID)->where('classID', $listClass)->first())
                    {
                        ($listClass) ? (DB::table('teacher_class')->insert(['userID'=>$getUserID, 'classID'=> $listClass])) : '';
                    }
                }
            }
            //upload photo
            $getPath    = 'public/appAssets/passport/user/';
            if($file){
                $originalExtension      = $file->getClientOriginalExtension();
                $imageNewName           = $this->randomUniqueCode() . '.' .$originalExtension;
                $path                   = $this->uploadBasePath() . '/' . $getPath;
                if($file->move($path, $imageNewName))
                {
                    User::where('id', $getUserID)->update(['photo'=>$imageNewName]);
                }
            }
            $Teacher = new Teacher;
            $Teacher->userID                 = $getUserID;
            $Teacher->guarantor_firstname    = $request->guarantorFirstName;
            $Teacher->guarantor_lastname     = $request->guarantorLastName;
            $Teacher->guarantor_telephone    = $request->guarantorTelephone;
            $Teacher->guarantor_address      = $request->guarantorAddress;
            $Teacher->guarantor_email        = $request->guarantorEmail;
            $Teacher->guarantor_occupation   = $request->guarantorOccupation;
            $Teacher->updated_at             = date('Y-m-d');
            if($Teacher->save()){
                return redirect()->route('createTeacher')->with('message', 'User registration was completed.');
            }
            
            return redirect()->route('createTeacher')->with('message', 'New user was created. <br /> NB.: Registration not completed');
        }
        return redirect()->route('createTeacher')->with('error', 'Sorry, we cannot create new user! Try again.');
    }

    //create update Teacher
    public function createUpdateTeaacher($userID)
    {   
        $data['allRole'] = Role::orderBy('roleID', 'Asc')->where('role_active', 1)->where('roleID', '<>', 1)->get();
        if(($userID == null) or !User::find($userID))
        {
            return redirect()->back()->with('error', "Sorry, we are having problem loading the selected user's details!");
        }else{
            $data['allClass']   = $this->getClass();
            $data['teacher']    = $this->pickOneUser($userID);
            $data['path']       = 'appAssets/passport/user/';

            return view('teacher.editTeacher', $data);
        }
        return redirect()->back()->with('error', "Sorry, will cannot find this user's details !");
    }


    //Update Teacher
    public function updateTeacherDetails(Request $request)
    {   
        $userID = $request['userID'];
        if(User::find($userID))
        {
            $this->validate($request, [
                'surname'            => ['required', 'string', 'min:3', 'max:100'],
                'otherName'          => ['required', 'string', 'min:3', 'max:100'],
                'admittedDate'       => ['required', 'date'],
                'gender'             => ['required', 'string'],
                'userType'           => ['required', 'numeric'],
                'designation'        => ['required', 'string'],
                'email'              => ['required', 'email', 'min:3', 'max:100'],  
            ]);
            //check image was attached
            $file       = $request->file('file');
            if(!empty($file) or $file != "")
            {
                $this->validate($request, [
                    'file'          => 'required|image|mimes:png,jpg,jpe,jpeg|max: 3072', 
                ]);
            } 
            //check password match
            if(!empty($request['password']) and (!empty($request['password_confirmation'])))
            {
                    $this->validate($request, [
                        'password'           => ['required', 'string', 'min:5', 'max:100', 'confirmed'],
                    ]);
            }
            //Update User
            $user = User::find($userID);
            $user->email        = $request->email;
            $user->name         = $request->surname;
            $user->other_name   = $request->otherName;
            $user->user_type    = $request->userType;
            $user->designation  = $request->designation;
            $user->gender       = $request->gender;
            $user->address      = $request->address;
            $user->telephone    = $request->phoneNumber;
            $user->admitted_date = $request->admittedDate;
            $user->updated_at     = date('Y-m-d');
            if($user->save())
            {
                if(!empty($request->teacherClass) and ($request->teacherClass > 0))
                {
                    foreach($request->teacherClass as $convertToArray){ 
                        $arrayClass[] = $convertToArray;
                    }
                    DB::table('teacher_class')->where('userID', $userID)->delete();
                    foreach($arrayClass as $listClass){ 
                        if(!DB::table('teacher_class')->where('userID', $userID)->where('classID', $listClass)->first())
                        {
                            ($listClass) ? (DB::table('teacher_class')->insert(['userID'=>$userID, 'classID'=> $listClass])) : '';
                        }
                    }
                }
                //Change Password
                if(!empty($request['password']) and (!empty($request['password_confirmation'])))
                {
                    $user = User::find($userID);
                    $user->password     = Hash::make(trim($request['password']));
                    $user->updated_at   = date('Y-m-d');
                    $user->save();
                }
                //upload photo
                $getPath    = 'public/appAssets/passport/user/';
                if($file){
                    $originalExtension      = $file->getClientOriginalExtension();
                    $imageNewName           = $this->randomUniqueCode() . '.' .$originalExtension;
                    $path                   = $this->uploadBasePath() . '/' . $getPath;
                    if($file->move($path, $imageNewName))
                    {
                        $getOldPath = User::where('id', $userID)->value('photo');
                        User::where('id', $userID)->update(['photo'=>$imageNewName]);
                        if(file_exists($path . $getOldPath))
                        {
                            unlink($path . $getOldPath);
                        }
                    }
                }
                $Teacher =  Teacher::find(Teacher::where('userID', $userID)->value('userInfoID'));
                $Teacher->guarantor_firstname    = $request->guarantorFirstName;
                $Teacher->guarantor_lastname     = $request->guarantorLastName;
                $Teacher->guarantor_telephone    = $request->guarantorTelephone;
                $Teacher->guarantor_address      = $request->guarantorAddress;
                $Teacher->guarantor_email        = $request->guarantorEmail;
                $Teacher->guarantor_occupation   = $request->guarantorOccupation;
                $Teacher->updated_at             = date('Y-m-d');
                if($Teacher->save()){
                    return redirect()->route('editTeacher', ['userID'=>$userID])->with('message', 'Your record was updated successfully.');
                }
                return redirect()->route('editTeacher', ['userID'=>$userID])->with('message', 'Some part of your records were updated.');
            }
        }
        return redirect()->route('editTeacher', ['userID'=>$userID])->with('error', 'Sorry, we cannot update your record now due to some errors! Please, Try again.');
    }

     //List all Teacher
    public function listAllTeacher()
    {
        $data['allClass'] = $this->getClass();
        $data['allUserList'] = $this->getUserList();

        return view('teacher.listTeacher', $data);
    }


    public function activateSuspendUser($userID, $statusID)
    {
        $message = "Sorry, we are having issue while updating your record. Try againg later.";
        if(User::find($userID))
        {
            User::where('id', trim($userID))->update([
                'suspend'     => $statusID,
                'updated_at'  => date('Y-m-d'),
            ]); 
            if($statusID == 0){
                $message = "This user has been activated.";
            }else{
                $message = "NOTE: This user has been suspended";
            }
        }
        return $message;
    }

    //View User
    public function viewUserDetails($userID)
    {
        if(User::find($userID))
        {
            $data['userDetails'] = $this->pickOneUser($userID);

            $data['userClass'] = DB::table('teacher_class')->where('userID', $userID)
               ->leftJoin('student_class', 'student_class.classID', '=', 'teacher_class.classID')
               ->get();

            $data['path'] = "appAssets/passport/user/";

            return view('teacher.viewTeacher', $data);
        }else{
            return redirect()->back()->with('error', 'Sorry, the user you are trying to look for cannot be found! Please try again.');
        }
    }


    //Delecte User
    public function removeTeacher($ID = null)
    {
        $success = 0;
        if(User::find($ID)){
            $success = User::where('id', $ID)->update(['suspend'=>1, 'deleted'=>1]);
        }
        if($success){
            return redirect()->route('createTeacher')->with('message', 'A user was deleted successfully and moved to recycle bin.');
        }
        return redirect()->route('createTeacher')->with('error', 'Sorry, we cannot delete this user from our system.');
        
    }

    //Get Teacher Registration Number
    private function getTeacherRegNo($suffix)
    {   
        $newTeacherRegNo = '';
        if(Session::get('getSchoolProfile')) 
       {
            $getAbbr = (($suffix) ? $suffix : Session::get('getSchoolProfile')->school_short_name);
            $lastID  = User::orderBy('id', 'Desc')->value('id');
            $newTeacherRegNo = $getAbbr.date('Y').(($lastID) ? $lastID : '01');
       }else{
            $newTeacherRegNo = '';
       }
       return $newTeacherRegNo;
    }


    //create Edit profile
    public function createEditProfile()
    {   
        ((Auth::check())  ? ($data['editUser'] = User::find(Auth::User()->id)) : $data['editUser'] = array());
        return view('profile.home', $data);
    }
    
    //
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'surname'            => ['required', 'string', 'min:3', 'max:100'],
            'otherName'          => ['required', 'string', 'min:3', 'max:100'],
            'emailAddress'       => ['required', 'email', 'min:3', 'max:100'],  
            //'phoneNumber'        => ['required', 'string'],
            //'homeAddress'        => ['required', 'numeric'],
        ]);
        //check password match
        if(!empty($request['password']) and (!empty($request['password_confirmation'])))
        {
                $this->validate($request, [
                    'password'           => ['required', 'string', 'min:5', 'max:100', 'confirmed'],
                ]);
        }
        //Update User
        $user = User::find(Auth::User()->id);
        $user->email        = $request->emailAddress;
        $user->name         = $request->surname;
        $user->other_name   = $request->otherName;
        $user->address      = $request->homeAddress;
        $user->telephone    = $request->phoneNumber;
        $user->updated_at   = date('Y-m-d');
        if($user->save())
        {
            //Change Password
            if(!empty($request['password']) and (!empty($request['password_confirmation'])))
            {
                $user = User::find(Auth::User()->id);
                $user->password     = Hash::make(trim($request['password']));
                $user->updated_at   = date('Y-m-d');
                $user->save();
            }
            
            //Send Email
            $schoolDetails = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->value('school_full_name');
            $schoolName = ($schoolDetails ? $schoolDetails : 'School Eportal');
            $user = User::find(Auth::User()->id);
            if($user)
            {
                $emailData = ([
                    'name'=> $user->name .' '. $user->other_name,
                    'email'=> $user->email, 
                    'address'=> $user->address, 
                    'phone'=>$user->telephone, 
                    'staffID'=>$user->userRegistrationId,
                    'post' =>$user->designation,
                    'schoolName' => $schoolName
                ]);
                try {
                    ($user->email ? Mail::to($user->email)->send(new sendEmailTeacherChangeAccount($emailData)) : '');
                } catch (\Exception $e) {
                    //return;
                }
            
            }
            
            return redirect()->route('updateProfile')->with('message', 'Your profile has been updated successfully');
        }else{
            return redirect()->route('updateProfile')->with('error', 'Sorry, we cannot update your profile now! Please again.');
        }
       
    }


}//end class
