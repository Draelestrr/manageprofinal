<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address'
    ];

    // Relación muchos a muchos con productos
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
                    ->withTimestamps();
    }
}
