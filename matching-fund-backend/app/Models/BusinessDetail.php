<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cooperative_id',
        'business_id',
    ];

    // RELATIONSHIPS

    // belongsTo (one to one) relationship with Business
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // belongsTo (one to one) relationship with Cooperative
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    // hasMany (one to many) relationship with Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
