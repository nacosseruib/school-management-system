<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class GradeRemark extends Model 
{
    protected $table        = 'grade_remark';
    protected $primaryKey   = 'graderemarkID';
    
    use Notifiable;

    protected $fillable = [
        'grade_remark', 
        'remark_description',
        'active',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
