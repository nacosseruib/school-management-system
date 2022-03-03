<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailOut extends Mailable
{
    use Queueable, SerializesModels;

    protected $message;
      
    public function __construct($getMessage)
    {
        $this->message = $getMessage;
    }


    public function build()
    { 
        return $this->from($this->message['senderEmail'], $this->message['senderName'])
                    ->subject($this->message['subject'])
                    ->markdown('Mails.sendEmailOutTemplate')
                    ->with($this->message);
    }
    
    
}//end class
