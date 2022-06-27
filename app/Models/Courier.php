<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'phone_number',
    ];

    // RELATIONSHIPS

    // hasMany (one to many) relationship with TransactionDetail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
