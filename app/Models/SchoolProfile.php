<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model 
{
    protected $table        = 'school_profile';
    protected $primaryKey   = 'id';
    
    use Notifiable;

    protected $fillable = [
        'school_short_name', 
        'school_full_name', 
        'address',
        'phone_no',
        'website',
        'email',
        'logo',
        'registration_no',
        'establishment_date',
        'slogan',
        'created_at',
        'updated_at',
        'active',
        'schoolID',
        'student_regID_format',
        'school_resumption_date',
        'use_auto_reg',
        'update_pulish_result',
        'day_school_open',
        'send_email',
        'report_sheet_template',
        'result_sheet_watermark',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
