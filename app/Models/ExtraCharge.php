<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCharge extends Model
{
    use HasFactory;

    protected $fillable = ['expense_id', 'description', 'amount'];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
