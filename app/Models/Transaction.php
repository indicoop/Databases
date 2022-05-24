<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'destination_address',
        'voucher_id',
        'note',
    ];

    // RELATIONSHIPS

    // belongsTo (one to one) relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // belongsTo (one to one) relationship with Voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // hasMany (one to many) relationship with TransactionDetail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
