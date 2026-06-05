<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'amount',
        'snap_token',
        'payment_url',
        'status',
    ];

    protected $casts = [
        'amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the order that this payment belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
