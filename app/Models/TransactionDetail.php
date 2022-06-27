<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public $table = 'transaction_details';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'courier_id',
        'cooperative_id',
        'total_pay',
        'payment_method_id',
        'status',
        'shipping_fee',
        'transaction_date',
    ];

    // RELATIONSHIPS

    // belongsTo (one to one) relationship with Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // belongsTo (one to one) relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // belongsTo (one to one) relationship with Courier
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    // belongsTo (one to one) relationship with Cooperative
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }
}
