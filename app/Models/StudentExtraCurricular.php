<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentExtraCurricular extends Model 
{
    protected $table        = 'extra_curricular';
    protected $primaryKey   = 'student_extraID';
    
    use Notifiable;

    protected $fillable = [
        'curricular_name', 
        'curricular_description', 
        'created_at',
        'active',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
