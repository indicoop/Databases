<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loans extends Model
{
    use HasFactory;
    protected $table = 'loans';
    protected $fillable = ['user_id', 'loan_date', 'amount', 'installment_principal', 'installment_interest', 'total_installment', 'installment_remaining', 'loan_type_id'];
    protected $primaryKey = 'id';

public function loan_types()
{
    return $this->belongsTo('App\Models\Loan_Types');
}

}

