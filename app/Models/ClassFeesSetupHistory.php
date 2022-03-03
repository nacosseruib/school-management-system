<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ClassFeesSetupHistory extends Model 
{
    protected $table        = 'class_fees_setup_history';
    protected $primaryKey   = 'classfeehistoryID';
    
    use Notifiable;

    protected $fillable = [
        'feeID', 
        'classID',
        'termID',
        'session_code',
        'fee_amount',
        'student_amount',
        'active',
        'created_at',
        'updated_at'
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
