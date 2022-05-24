<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'installment_type',
        'installment_number',
        'pay_date',
        'lateness_date',
        'total_installment',
        'interest',
        'fine',
        'total_pay',
    ];

    // RELATIONSHIPS

    // hasMany - one to many relationship with loan
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
