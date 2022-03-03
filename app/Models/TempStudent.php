<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TempStudent extends Model 
{
    protected $table        = 'temp_student';
    protected $primaryKey   = 'studentID';
    
    use Notifiable;

    protected $fillable = [
        'student_regID', 
        'student_roll',
        'student_class', 
        'class_name',
        'student_firstname',
        'student_lastname',
        'student_gender',
        'student_address',
        'student_curricularID',
        'parent_firstname',
        'parent_lastname',
        'parent_address',
        'parent_telephone',
        'parent_email',
        'parent_occupation',
        'admitted_date',
        'created_at',
        'active',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
