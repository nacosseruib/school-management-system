<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\SchoolProfile;

class sendEmailTeacherChangeAccount extends Mailable
{
    use Queueable, SerializesModels;

    protected $message;
      
    /*public function __construct(Request $request)
    {
        $message = $request;
    }*/
    
    public function __construct($getMessage)
    {
        $this->message = $getMessage;
    }


    public function build()
    {
        $schoolDetails  = SchoolProfile::where('id', SchoolProfile::orderBy('id', 'Desc')->value('id'))->where('active', 1)->select('school_full_name', 'email')->first();
        $schoolEmail    = ($schoolDetails ? $schoolDetails->email : 'School Email not fund');
        $schoolName     = ($schoolDetails ? $schoolDetails->school_full_name : 'School Nname not fund');
        //
        return $this->from($schoolEmail, $schoolName)
                    ->subject('Your account has been updated')
                    ->markdown('Mails.teacherChangeAccountTemplate')
                    //->view('message')
                    ->with($this->message);
    }
    
    
}//end class
