<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    // Accessors
    public function getNameAttribute()
    {
        return $this->product->title;
    }

    public function getPriceAttribute()
    {
        // Use variant price if variant is selected, otherwise use product lowest price
        return $this->variant ? $this->variant->price : $this->product->lowest_price;
    }

    public function getImageAttribute()
    {
        // Use variant image if variant is selected and has image, otherwise use product image
        if ($this->variant && $this->variant->image) {
            return asset('storage/variant/' . $this->variant->image);
        }
        return $this->product->image ? asset('storage/product/' . $this->product->image) : asset('images/no-image.jpg');
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForCurrentUser($query)
    {
        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        }
        return $query->where('session_id', session()->getId());
    }
}
