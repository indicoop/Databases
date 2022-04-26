<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
    ];

    // RELATIONSHIPS

    // hasMany (one to many) relationship with Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
