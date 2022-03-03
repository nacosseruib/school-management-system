<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\Models\Module;
use App\Models\SubModule;
use App\Models\AssignSubmoduleRole;
use App\Models\Role;
use App\Models\SchoolProfile;
use App\Models\SchoolSession; 
use App\User;
use Auth;
use Schema;
use Session;
use DB;

class RolePermissionController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    //create Role
    public function createRole()
    {
        $data['allRole'] = Role::orderBy('roleID', 'Asc')->get();
        //Get Edit Data
        (Session::get('editRole') ? $data['editRole'] = Session::get('editRole') : '');
        //
        return view('RolePermission.role', $data);
    }


    public function saveRole(Request $request)
    {
        $this->validate($request, [
            'roleName'         => 'required|regex:/^[a-zA-Z0-9,.!?\-)\( ]*$/|max:100',
            'roleStatus'       => 'required|max:2',
        ]);
        $roleName           = trim($request['roleName']);
        $roleActive         = trim($request['roleStatus']);
        $roleID             = trim($request['editRoleID']);
        $message = 'Sorry we cannot add/update your record now. Please try again !';
        $success = null;
        if(Role::find($roleID and $roleID <> null)){
            //Update
            $role = Role::find($roleID); 
            $role->role_name    = $roleName;
            $role->role_active  = $roleActive;
            $role->updated_at   = date('Y-m-d');
            $success = $role->save();
            Session::forget('editRole');
            $message = 'Your record was updated successfully.';
        }else{
            $this->validate($request, [
                'roleName'   => 'required|regex:/^[a-zA-Z0-9,.!?\-)\( ]*$/|max:1000|unique:role,role_name',
            ]);
            //Insert
            $role = New Role;
            $role->role_name    = $roleName;
            $role->role_active  = $roleActive;
            $role->updated_at   = date('Y-m-d');
            $success = $role->save();
            Session::forget('editRole');
            $message = 'New role has been created successfully.';
        }
        //
        if($success)
        {
            return redirect()->route('createRole')->with('message', $message);
        }else{
            return redirect()->route('createRole')->with('error', $message);
        }
        
    }//
    

    // Remove Role
    public function removeRole($roleID)
    {
        $success = 0;
        if(Role::find($roleID)){
            $success = Role::where('roleID', $roleID)->delete();
        }
        if($success){
            return redirect()->route('createRole')->with('message', 'Your record was deleted successfully.');
        }
        return redirect()->route('createRole')->with('error', 'Sorry, we cannot delete this record is already in use. Try again.');
        
    }

    // show edit Role
    public function editRole($roleID)
    {
        if(Role::find($roleID)){
            Session::put('editRole', Role::find($roleID));
        }else{
            Session::forget('editRole');
        }
        return redirect()->route('createRole');
        
    }

    // cancel edit
    public function cancelEditRole()
    {
        Session::forget('editRole');

        return redirect()->route('createRole')->with('message', 'Edit was canceled. You can now add new recored.');
    }

    ////////////// END ROLE CREATION///////////////////////////////////////





    /////////////////////////START ASSIGNING SUBMODULE TO ROLE//////////////
    //create Assignment page
    public function createSubmoduleToRole()
    {   
        $permission = array();
        $assignSubmoduleID = array();
        $data['allUser'] = User::orderBy('name', 'Asc')->where('suspend', 0)->where('deleted', 0)->select('id', 'name', 'other_name', 'userRegistrationId')->get();
        $data['allRole'] = Role::orderBy('roleID', 'Asc')->where('role_active', 1)->get();
        $data['subModuleAssignment'] = AssignSubmoduleRole::orderBy('roleID', 'Asc')->where('assign_submodule_active', 1)->get();
        $data['allSubModule'] = SubModule::orderBy('submodule_rank', 'Asc')->paginate(30); //->where('submodule_active', 1)
        $data['totalRole'] = count($data['allRole']);
        //
        foreach($data['allSubModule'] as $keyModule=>$listModule)
        {
            foreach($data['allRole'] as $keyRole=>$listRole)
            {   
                $checkAssignment = ((AssignSubmoduleRole::where('submoduleID', $listModule->submoduleID)->where('roleID', $listRole->roleID)->first()) ? 1 : 0);
                $permission[$keyModule.$keyRole] = $checkAssignment;
                $assignSubmoduleID[$keyModule.$keyRole] = AssignSubmoduleRole::where('submoduleID', $listModule->submoduleID)->where('roleID', $listRole->roleID)->value('assign_submoduleID');
            }
        }
        $data['getPermission'] = $permission;
        $data['assignSubmoduleID'] = $assignSubmoduleID;
        //
        return view('RolePermission.assignSubmoduleRole', $data);
    }

    //Save Asignment
    public function saveSubmoduleToRole(Request $request) 
    {   
        //Assign Submodule Role
        $permission  = $request['assignSubmoduleRole'];
    
        //Assign Submodule ID
        $assignSubmoduleRoleID  = $request['assignSubmoduleID'];
        foreach ($assignSubmoduleRoleID as $assignSubID) {
            $assignSubmoduleIDUpdate[] = $assignSubID;
        }

        foreach ($permission as $getArrayKey=>$arrayPermission) 
        {
            if($arrayPermission <> 0)
            {
                    $explodeArray = explode('-', $arrayPermission);
                    //check if not already assigned then, Insert
                    if(!AssignSubmoduleRole::where('submoduleID', $explodeArray[0])->where('roleID', $explodeArray[1])->first())
                    {   
                        $assignSubmoduleRole = New AssignSubmoduleRole;
                        $assignSubmoduleRole->submoduleID   = $explodeArray[0];
                        $assignSubmoduleRole->roleID        = $explodeArray[1];
                        $assignSubmoduleRole->moduleID      = SubModule::find($explodeArray[0])->moduleID;
                        $assignSubmoduleRole->updated_at    = date('Y-m-d');
                        $assignSubmoduleRole->save();
                    }else{ 
                        //Update
                        if(!empty($assignSubmoduleIDUpdate))
                        {
                            $assignSubmoduleRole = AssignSubmoduleRole::find($assignSubmoduleIDUpdate[$getArrayKey]);
                            $assignSubmoduleRole->submoduleID   = $explodeArray[0];
                            $assignSubmoduleRole->roleID        = $explodeArray[1];
                            $assignSubmoduleRole->moduleID      = SubModule::find($explodeArray[0])->moduleID;
                            $assignSubmoduleRole->updated_at    = date('Y-m-d');
                            $assignSubmoduleRole->save();
                        }
                    }

            }else{
                //Remove
                $assignSubmoduleRole = AssignSubmoduleRole::find($assignSubmoduleIDUpdate[$getArrayKey]);
                ($assignSubmoduleRole ? $assignSubmoduleRole->delete() : '');
            }
        }
        //
        return redirect()->route('createSubmoduleAssignment')->with('message', 'Your records were updated successfully.');
    }

    //////////////////////// END SUBMODULE ASSIGNMENT TO ROLE///////////////



    
    /////////////////////////ASSIGN ROLE TO USER//////////////////////////

    public function assignRoleToUser(Request $request) 
    {
        $this->validate($request, [
            'userName'       => 'required|alpha_num',
            'roleName'       => 'required|alpha_num',
        ]);
        $userID = $request['userName'];
        $roleID = $request['roleName'];
        $message = "This user does not exist. Try again.";
        $success = 0;
        $user = User::find($userID);
        if($user)
        {
            if(Role::find($roleID))
            {
                $user->user_type = $roleID;
                $success = $user->save();
                $message = $user->name.' '. $user->other_name ." has been assigned to ". Role::find($roleID)->role_name ."'s role successfully.";
                $this->doAfterLogin();
            }else{
                $message = "Sorry we cannot not assign this user! Please, try again.";
            }
        }
        //
        if($success)
        {
            return redirect()->route('createSubmoduleAssignment')->with('message', $message);
        }
        return redirect()->route('createSubmoduleAssignment')->with('error', $message);
    }

    ///////////////////////    END ASSIGNMENT TO USER////////////////////
    
    
    
    ////////////////////////////////DO AFTER LOGIN FUNCTIONS/////////////////////
    public function doAfterLogin()
    {
        Session::forget('userMenuModule');
        Session::forget('userMenu');
        Session::forget('roleName');
        
        //School Profile
        $profile = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->first();
        (($profile) ? Session::put('getSchoolProfile', $profile) : '');
        //School logo path
        Session::put('path', 'appAssets/schoolLogo/');

        //School Session
        $schoolSession = SchoolSession::where('school_session.active', 1)->join('term', 'term.termID', '=', 'school_session.current_termID')->first();
        (($schoolSession) ? Session::put('getSchoolSession', $schoolSession) : '');


        //Get User Menus
        $userMenu = array();
        $subModuleList = array();

        //GET ALL MODULES
        if((Auth::user()->user_type == 1))
        {   
            //super admin role
            $allModule = Module::orderBy('module_rank', 'Asc')
                ->where('module_active', 1)
                ->get();
        }else{
            //other roles
            $allModule = AssignSubmoduleRole::orderBy('module.module_rank', 'Asc')
                ->join('module', 'module.moduleID', '=', 'assign_submodule_role.moduleID')
                ->where('module.module_active', 1)
                ->where('assign_submodule_role.roleID', Auth::user()->user_type)
                ->where('assign_submodule_role.assign_submodule_active', 1)
                ->select('module.module_name', 'module.moduleID', 'module.module_rank', 'module.module_url', 'module.module_active', 'module.module_icon', 'assign_submodule_role.moduleID')
                ->groupBy('assign_submodule_role.moduleID')
                ->get();
        }

        //GET ALL SUBMODULE
        if($allModule){
            foreach($allModule as $key=>$listModule)
            {
                if((Auth::user()->user_type == 1))
                {   
                    //supre admin role
                    $subModuleList = SubModule::orderBy('submodule.submodule_rank', 'Asc')
                    ->join('module', 'module.moduleID', '=', 'submodule.moduleID')
                    ->where('submodule_active', 1)
                    ->where('submodule.moduleID', $listModule->moduleID)
                    ->get();
                    //Get user's Menus
                    (($subModuleList) ? $userMenu[$key.$listModule->moduleID] = $subModuleList : $userMenu[$key.$listModule->moduleID] = null);
                }else{
                    //other roles
                    $subModuleList = AssignSubmoduleRole::orderBy('submodule.submodule_rank', 'Asc')
                    ->join('submodule', 'submodule.submoduleID', '=', 'assign_submodule_role.submoduleID')
                    ->join('module', 'module.moduleID', '=', 'assign_submodule_role.moduleID')
                    ->where('submodule_active', 1)
                    ->where('submodule.moduleID', $listModule->moduleID)
                    ->where('assign_submodule_role.roleID', Auth::user()->user_type)
                    ->get();
                    //Get user's Menus
                    (($subModuleList) ? $userMenu[$key.$listModule->moduleID] = $subModuleList : $userMenu[$key.$listModule->moduleID] = null);
                }
            }
        }else{
            $userMenu = null;
        }
        //Set User Menu Module
        (($allModule) ? Session::put('userMenuModule', $allModule) : '');
        //Set User Menu SubModule 
        (($allModule) ? Session::put('userMenu', $userMenu) : '');

        //SET LAST LOGIN AND ROLE NAME
        Session::put('roleName', (Role::find(Auth::user()->user_type) ? Role::find(Auth::user()->user_type)->role_name : ''));
        
    }
    ////////////////////////////////END DO AFTER LOGIN FUNCTIONS/////////////////////
    
    

}
