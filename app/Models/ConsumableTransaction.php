<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumableTransaction extends Model
{
    protected $fillable = [
        'consumable_id',
        'user_id',
        'type', // 'in' or 'out'
        'quantity',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the consumable associated with the transaction.
     */
    public function consumable(): BelongsTo
    {
        return $this->belongsTo(Consumable::class);
    }

    /**
     * Get the user who registered the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
