<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'purchase_price',
        'sale_price',
        'stock',
        'stock_min',
        'image_path'
    ];

    // Relación con categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación muchos a muchos con proveedores
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
                    ->withTimestamps();
    }

    // Relación con entradas de stock
    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }

    // Relación muchos a muchos con ventas
    public function sales()
    {
        return $this->belongsToMany(Sale::class)
                    ->withPivot('quantity', 'unit_price', 'subtotal')
                    ->withTimestamps();
    }

    // Relación muchos a muchos con gastos
    public function expenses()
    {
        return $this->belongsToMany(Expense::class, 'expense_product')
                    ->withPivot('quantity', 'purchase_price', 'subtotal')
                    ->withTimestamps();
    }

    // Actualización de stock
    public function updateStock($quantity, $operation = 'decrement')
    {
        if ($operation === 'decrement') {
            $this->decrement('stock', $quantity);
        } else {
            $this->increment('stock', $quantity);
        }
    }
}
