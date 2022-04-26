<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuesType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
    ];

    // RELATIONSHIPS

    // hasMany - one to many relationship with Dues
    public function dues()
    {
        return $this->hasMany(Dues::class, 'dues_type_id');
    }
}
