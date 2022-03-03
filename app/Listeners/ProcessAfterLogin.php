<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;
use App\Http\Controllers\RolePermissionController;
use App\Models\SchoolProfile;
use App\Models\SchoolSession; 
use App\Models\AssignSubmoduleRole;
use App\Models\SubModule;
use App\Models\Module;
use App\Models\Role;
use App\User;
use Auth;
use DB;
use Session;

class ProcessAfterLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserDetails  $event
     * @return void
     */
    public function handle($event)
    {
        ///////////////START DO AFTER LOGIN ROUTINE FUNCTIONS/////////////
        $object = new RolePermissionController;
        $object->doAfterLogin();
        $userDetails = User::find(Auth::user()->id);
        if($userDetails)
        {
            $userDetails->last_login    = $userDetails->current_login;
            $userDetails->current_login = date('Y-m-d h:i:sa');
            $userDetails->save();
        }
    }


}//end class