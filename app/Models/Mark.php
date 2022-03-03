<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model 
{
    protected $table        = 'mark';
    protected $primaryKey   = 'markID';
    
    use Notifiable;

    protected $fillable = [
        'studentID', 
        'classID', 
        'subjectID',
        'scoretypeID',
        'scoretype_name',
        'session_code',
        'termID',
        'term_name',
        'score',
        'computed_by_ID',
        'computed_by',
        'created_at',
        'updated_at',
        'active',
        'publish',
        'publish_date_time',
        'edit',
        'score_session',
        'total_score',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
