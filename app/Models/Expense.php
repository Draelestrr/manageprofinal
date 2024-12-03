<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
        'user_id',
        'receipt_image_path'
    ];

    // Relación con usuario (quien registró el gasto)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con cargos adicionales
    public function extraCharges()
    {
        return $this->hasMany(ExtraCharge::class);
    }

    // Relación muchos a muchos con productos (a través de la tabla pivote expense_product)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'expense_product')
                    ->withPivot('quantity', 'purchase_price', 'subtotal')
                    ->withTimestamps();
    }
}
