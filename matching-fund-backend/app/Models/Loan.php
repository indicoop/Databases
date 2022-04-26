<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_date',
        'amount',
        'installment_principal',
        'installment_interest',
        'total_installment',
        'installment_remaining',
        'loan_type_id',
    ];

    // RELATIONSHIPS

    // belongsTo - one to one relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // belongsTo - one to one relationship with LoanType
    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id');
    }
}
