<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
//use Illuminate\Support\Facades\Mail;
//use App\User;
//use App\Models\SchoolProfile;
//use App\Models\EmailLog;
//use App\Mail\SendEmailOut;
use Auth;
use Session;
use DB;

class EmailController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function createEmail()
    {   
        $data['senderName']   = ucwords(Auth::user()->name .' '. Auth::user()->other_name);
        $data['senderEmail']  = (Auth::user()->email);
        //
        return view('SendEmail.createEmail', $data);
    }//fun


    public function sendEmail(Request $request)
    {
        //Validation
         $this->validate($request,[
            'senderName'        => 'required|string',
            'senderEmail'       => 'required|email',
            //'subject'         => 'required|string',
            //'replyToEmail'    => 'required|alpha_num',
            'message'           =>  'required|string',
            'recipients'        => 'required|string',
        ]);
        $arrayAllRecipients = array();
        //
        $allRecipients      = explode(",", $request['recipients']);
        $getAllRecipients   = count($allRecipients);
        foreach($allRecipients as $noCommaText)
        {
            $arrayAllRecipients[] = str_replace('"', '', str_replace(']', '', str_replace('[', '', str_replace('"', '', $noCommaText))));
        }
        //
        if($getAllRecipients < 1){
            return redirect()->back()->with('warning', 'No recipient found ! Please enter the recipient(s) you want to send your email to.');
        }
        //
       
        $count = 0;
        //send email
        $count = $this->sendAnyEmail($arrayAllRecipients, $request['subject'], $request['message'], $request['senderName'],  $request['senderEmail']);
        
        if($count > 0)
        {    
            return redirect()->route("createEmail")->with( 'message', $count . " Email(s) was sent to recipient(s) successful!" );
        }else{
            return redirect()->route("createEmail")->with( 'error', "No Email was sent!" );
        }
    }
    
    
}//end class
