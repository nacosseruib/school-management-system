<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentPaymentFee extends Model 
{
    protected $table        = 'student_payment_fees';
    protected $primaryKey   = 'paymentfeesID';
    
    use Notifiable;

    protected $fillable = [
        'session_code', 
        'classID',
        'studentID',
        'termID',
        'className',
        'studentRegID',
        'studentName',
        'payment_description',
        'payment_date',
        'created_at',
        'updated_at',
        'total_amount_due',
        'balance_due',
        'amount_paid',
        'paid_by_name',
        'paid_by_email',
        'paid_by_phone',
        'parent_name',
        'parent_email',
        'parent_phone',
        'platform_paid_from',
        'ip_address',
        'paid_location',
        'tokenID',
        'transactionID',
        'payment_status',
        'payment_status_code',
        'receipt_printed_counter',
        'active',
    ];
    
    protected $hidden = [
        
    ];
    
    public $timestamps = false;
}
