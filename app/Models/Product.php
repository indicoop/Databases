<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_detail_id',
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
        return $this->belongsTo(BusinessDetail::class, 'business_detail_id');
    }

    // belongsTo (one to one) relationship with ProductCategory
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    // hasOne (one to one) relationship with Voucher
    public function voucher()
    {
        return $this->hasOne(Voucher::class, 'id');
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

    // hasMany (one to many) relationship with Rating
    public function rating()
    {
        // return total rating of product by rating_value
        return $this->hasOne(Rating::class, 'product_id')->selectRaw('sum(rating_value) / count(user_id) as rating');
    }

    // total_transaction_count where transasction_details.status = 'success'
    public function totalTransactionCount()
    {
        return $this->hasOne(Transaction::class, 'product_id')->selectRaw('count(product_id) as total_transaction_count')->whereHas('transactionDetails', function ($query) {
            $query->where('status', 'success');
        });
    }

    // total quantity of product in transaction_details.status = 'success'
    public function totalQuantity()
    {
        return $this->hasOne(Transaction::class, 'product_id')->selectRaw('sum(quantity) as total_quantity')->whereHas('transactionDetails', function ($query) {
            $query->where('status', 'success');
        });
    }
}
