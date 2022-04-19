<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'period',
        'interest',
        'fine',
    ];

    // RELATIONS

    // hasMany - Loan
    public function loans()
    {
        return $this->hasMany(Loan::class, 'loan_type_id', 'id');
    }
}