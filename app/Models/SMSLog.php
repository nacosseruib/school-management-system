<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SMSLog extends Model 
{
    protected $table        = 'sms_log';
    protected $primaryKey   = 'smslogID';
    
    use Notifiable;

    protected $fillable = [
        'receiver', 
        'senderID',
        'sender_name',
        'message',
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
