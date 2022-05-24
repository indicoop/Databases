<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dues extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_pay',
        'dues_amount',
        'dues_type_id',
    ];

    // RELATIONSHIPS
    
    // belongsTo - one to one relationship with DuesType
    public function duesType()
    {
        return $this->belongsTo(DuesType::class, 'dues_type_id');
    }

    // belongsTo - one to one relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
