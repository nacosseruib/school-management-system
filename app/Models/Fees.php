<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Fees extends Model 
{
    protected $table        = 'fees';
    protected $primaryKey   = 'feessetupID';
    
    use Notifiable;

    protected $fillable = [
        'fees_name', 
        'fees_description',
        'status',
        'amount',
        'fees_occurent_type',
        'core_fee',
        'created_at',
        'updated_at'
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
