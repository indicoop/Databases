<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stash extends Model
{
    use HasFactory;

    public $table = 'stashs';

    protected $fillable = [
        'user_id',
        'beginning_balance',
        'ending_balance',
        'stash_date',
        'stash_amount',
    ];

    // RELATIONSHIPS

    // belongsTo - one to one relationship with User

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
