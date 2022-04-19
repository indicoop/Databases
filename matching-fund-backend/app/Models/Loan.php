<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'interest', 'duration', 'start_date', 'end_date', 'status'
    ];

    // RELATIONS

    // belongsTo - User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // belongsTo - LoanType
    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id', 'id');
    }
}