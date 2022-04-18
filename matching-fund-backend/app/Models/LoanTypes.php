<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTypes extends Model
{
    use HasFactory;
    protected $table = 'loan_types';
    protected $fillable = ['type', 'period', 'interest', 'fine'];
    protected $primaryKey = 'id';

    public function loans()
    {
        return $this->hasMany('App\Models\Loans');
    }
}
