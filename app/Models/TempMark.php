<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TempMark extends Model 
{
    protected $table        = 'temp_mark';
    protected $primaryKey   = 'markID';
    
    use Notifiable;

    protected $fillable = [
        'studentID',
        'student_regID', 
        'classID', 
        'class_name',
        'subjectID',
        'subject_name',
        'session_code',
        'termID',
        'term_name',
        'test1',
        'test2',
        'exam',
        'computed_by_ID',
        'computed_by',
        'created_at',
        'updated_at',
        'active',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
