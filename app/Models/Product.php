<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'specification',
        'location',
        'image',
        'rating',
        'sold',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'sold' => 'integer',
    ];

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    // Accessor untuk mendapatkan harga terendah dari semua varian
    public function getLowestPriceAttribute()
    {
        return $this->variants()->min('price') ?? 0;
    }

    // Accessor untuk mendapatkan total stok dari semua varian
    public function getTotalStockAttribute()
    {
        return $this->variants()->sum('stock') ?? 0;
    }
}

