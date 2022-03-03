<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PublicSession extends Model 
{
    protected $table        = 'public_session';
    protected $primaryKey   = 'publicSessionID';
    
    use Notifiable;

    protected $fillable = [
        'session_name', 
        'description',
        'class_name',
        'school_term',
        'school_termID',
        'mid_full_term',
        'score_type',
        'userID',
        'created_at',
        'updated_at',
        'active',
    ];

    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
