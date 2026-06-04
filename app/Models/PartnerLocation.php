<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLocation extends Model
{
    protected $table = 'partner_locations';

    protected $fillable = [
        'name',
        'image',
        'address',
        'operating_hours',
        'is_active',
        'link',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
