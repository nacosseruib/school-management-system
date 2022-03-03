<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SchoolSession extends Model 
{
    protected $table        = 'school_session';
    protected $primaryKey   = 'sessionID';
    
    use Notifiable;

    protected $fillable = [
        'session_code', 
        'session_name',
        'current_termID',
        'active',
        'created_at',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
