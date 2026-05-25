<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'category',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function promos(): BelongsToMany
    {
        return $this->belongsToMany(Promo::class, 'promo_products');
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
