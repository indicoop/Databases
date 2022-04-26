<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    // RELATIONSHIPS

    // belongsTo (one to one) relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
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
}
