<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentFeeSetup extends Model 
{
    protected $table        = 'student_fees_setup';
    protected $primaryKey   = 'studentfeesetupID';
    
    use Notifiable;

    protected $fillable = [
        'feeID', 
        'additional_fee',
        'classID',
        'studentID',
        'active',
        'created_at',
        'amount',
        'termID',
        'session_code',
        'fees_name',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
