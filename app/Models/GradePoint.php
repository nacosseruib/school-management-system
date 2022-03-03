<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class GradePoint extends Model 
{
    protected $table        = 'grade_point';
    protected $primaryKey   = 'gradeID';
    
    use Notifiable;

    protected $fillable = [
        'grade_for', 
        'mark_from',
        'mark_to',
        'grade_point',
        'grade_point_remark',
        'grade_remark',
        'class_teacher_comment',
        'principal_comment',
        'active',
        'created_at',
        'updated_at',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
