<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Term extends Model 
{
    protected $table        = 'term';
    protected $primaryKey   = 'termID';
    
    use Notifiable;

    protected $fillable = [
        'term_code', 
        'term_name',
        'active',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
