<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'cooperative_id',
        'code',
        'discount',
        'effective_date',
        'expired_date',
    ];

    // RELATIONSHIPS

    // belongsTo (one to one) relationship with Cooperative
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    // hasMany (one to many) relationship with Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // hasone (one to one) relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'voucher_id', 'id');
    }
}
