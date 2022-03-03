<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class RegistrationFormat extends Model 
{
    protected $table        = 'registration_format';
    protected $primaryKey   = 'reg_formatID';
    
    use Notifiable;

    protected $fillable = [
        'reg_format', 
        'updated_at',
        'active',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
