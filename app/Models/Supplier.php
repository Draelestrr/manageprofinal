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

   // En app/Models/Supplier.php
    public function products()
    {
    return $this->hasMany(Product::class);  // Un proveedor puede tener varios productos
    }

}
