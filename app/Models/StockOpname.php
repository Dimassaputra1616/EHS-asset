<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpname extends Model
{
    protected $fillable = [
        'consumable_id',
        'user_id',
        'system_stock',
        'physical_stock',
        'difference',
        'status',
        'notes',
        'opname_date',
    ];

    /**
     * Get the consumable audited.
     */
    public function consumable(): BelongsTo
    {
        return $this->belongsTo(Consumable::class);
    }

    /**
     * Get the user who performed the audit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
