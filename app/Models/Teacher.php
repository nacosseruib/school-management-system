<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model 
{
    protected $table        = 'teacher';
    protected $primaryKey   = 'userInfoID';
    
    use Notifiable;

    protected $fillable = [
        'userID',
        'guarantor_firstname',
        'guarantor_lastname',
        'guarantor_address',
        'guarantor_telephone',
        'guarantor_email',
        'guarantor_occupation',
        'created_at',
        'updated_at',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
