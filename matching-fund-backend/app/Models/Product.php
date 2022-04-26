<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_business_id',
        'product_category_id',
        'name',
        'price',
        'stock',
        'description',
        'thumbnail',
        'production_date',
        'discount',
        'weight',
        'voucher_id',
    ];

    // RELATIONSHIP

    // belongsTo (one to one) relationship with BusinessDetail
    public function businessDetail()
    {
        return $this->belongsTo(BusinessDetail::class, 'detail_business_id');
    }

    // belongsTo (one to one) relationship with ProductCategory 
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    // hasOne (one to one) relationship with Voucher
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'voucher_id');
    }

    // hasMany (one to many) relationship with Whislist
    public function whislists()
    {
        return $this->hasMany(Whislist::class);
    }

    // hasMany (one to many) relationship with Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
