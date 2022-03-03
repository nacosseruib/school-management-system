<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ScoreType extends Model 
{
    protected $table        = 'score_type';
    protected $primaryKey   = 'scoretypeID';
    
    use Notifiable;

    protected $fillable = [
        'score_type_code', 
        'score_type', 
        'active',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
