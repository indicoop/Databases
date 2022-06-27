<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'registration_number',
        'status',
        'effective_date',
        'status_grade',
        'date_of_establishment',
        'address',
        'email',
        'phone_number',
        'form_of_cooperative',
        'certificate',
        'legal_entity_certificate',
        'is_verified',
        'profile_picture',
    ];

    // RELATIONSHIPS

    // hasMany (one to many) relationship with users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // hasMany (one to many) relationship with BusinessDetail
    public function businessDetails()
    {
        return $this->hasMany(BusinessDetail::class);
    }

    // hasMany (one to many) relationship with Voucher
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    // hasMany (one to many) relationship with TransactionDetail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // get total quantity transaction of product by cooperative
    public function getTotalQuantityTransactionOfProductByCooperative($product_id)
    {
        $total_quantity = 0;
        $transaction_details = $this->transactionDetails;
        foreach ($transaction_details as $transaction_detail) {
            $total_quantity += $transaction_detail->getTotalQuantityTransactionOfProductByTransactionDetail($product_id);
        }
        return $total_quantity;
    }
}
