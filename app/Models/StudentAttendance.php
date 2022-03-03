<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model 
{
    protected $table        = 'student_attendance';
    protected $primaryKey   = 'attendanceID';
    
    use Notifiable;

    protected $fillable = [
        'studentID', 
        'classID', 
        'termID', 
        'session_code', 
        'total_school_open', 
        'total_present', 
        'total_absent', 
        'created_at',
        'updated_at',
        'active',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
