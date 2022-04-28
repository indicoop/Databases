<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    // table name
    protected $table = 'business';

    protected $fillable = [
        'name',
        'category'
    ];

    // RELATIONSHIPS

    // hasMany (one to many) relationship with BusinessDetail
    public function businessDetails()
    {
        return $this->hasMany(BusinessDetail::class);
    }
}
