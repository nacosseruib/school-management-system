<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Student extends Model 
{
    protected $table        = 'student';
    protected $primaryKey   = 'studentID';
    
    use Notifiable;

    protected $fillable = [
        'student_regID', 
        'student_roll',
        'student_class', 
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
        'admitted_class',
        'admitted_session',
        'photo',
        'date_of_birth',
        'religion',
        'nationality',
        'state',
        'home_town',
        'school_type',
        'updated_at',
        'created_at',
        'graduate',
        'withdraw',
        'active',
        'deleted',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
