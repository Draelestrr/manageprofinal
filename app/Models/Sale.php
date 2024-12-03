<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'total'
    ];

    // Relación con cliente
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación muchos a muchos con productos (a través de la tabla pivote product_sale)
    public function products()
    {
    return $this->belongsToMany(Product::class)
        ->withPivot('quantity', 'unit_price', 'subtotal')
        ->withTimestamps();
    }

}
