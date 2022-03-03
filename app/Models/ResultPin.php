<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ResultPin extends Model 
{
    protected $table        = 'result_pin';
    protected $primaryKey   = 'pinID';
    
    use Notifiable;

    protected $fillable = [
        'pin', 
        'pin_token',
        'studentID',
        'school_session',
        'school_termID',
        'school_term_name',
        'classID',
        'pin_type',
        'pin_expire',
        'is_pin_active',
        'send_email',
        'generated_userID',
        'created_at',
        'updated_at',
        'user_no_of_time_use',
        'user_pi_address',
        'user_location',
        'user_last_login',
        'user_route',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
