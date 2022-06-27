<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating_value',
    ];

    // RELATIONSHIP

    // belongsTo (one to one) relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // belongsTo (one to one) relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
