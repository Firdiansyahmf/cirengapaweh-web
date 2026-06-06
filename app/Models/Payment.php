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
        'bank',
        'va_number',
        'biller_code',
        'qr_code_url',
        'expiry_time',
        'amount',
        'snap_token',
        'payment_url',
        'status',
    ];

    protected $casts = [
        'amount' => 'integer',
        'expiry_time' => 'datetime',
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
