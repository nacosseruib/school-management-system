<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ClassFeesSetup extends Model 
{
    protected $table        = 'class_fees_setup';
    protected $primaryKey   = 'classfeeID';
    
    use Notifiable;

    protected $fillable = [
        'feeID', 
        'classID',
        'active',
        'created_at',
        'updated_at'
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
