<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model 
{
    protected $table        = 'student_class';
    protected $primaryKey   = 'classID';
    
    use Notifiable;

    protected $fillable = [
        'class_code', 
        'class_name', 
        'description',
        'created_at',
        'active',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
