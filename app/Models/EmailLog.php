<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model 
{
    protected $table        = 'email_log';
    protected $primaryKey   = 'emailID';
    
    use Notifiable;

    protected $fillable = [
        'userID',
        'sender_email',
        'sender_name',
        'receiver_email',
        'message',
        'subject',
        'ip_address',
        'location',
        'route',
        'last_login',
        'created_at',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
