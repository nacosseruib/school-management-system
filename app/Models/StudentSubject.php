<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model 
{
    protected $table        = 'student_subject';
    protected $primaryKey   = 'subjectID';
    
    use Notifiable;

    protected $fillable = [
        'subject_code', 
        'classID', 
        'subject_name', 
        'subject_description',
        'max_ca1',
        'max_ca2',
        'max_exam',
        'active',
        'created_at',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
