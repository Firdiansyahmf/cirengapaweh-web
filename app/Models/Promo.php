<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promo extends Model
{
    protected $fillable = [
        'title',
        'promo_code',
        'description',
        'promo_type',
        'discount_percentage',
        'max_usage',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promo_products');
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->end_date);
    }

    public function isStarted(): bool
    {
        return now()->isAfter($this->start_date) || now()->isSameDay($this->start_date);
    }

    public function isActive(): bool
    {
        return $this->is_active && $this->isStarted() && !$this->isExpired() && $this->used_count < $this->max_usage;
    }
}
