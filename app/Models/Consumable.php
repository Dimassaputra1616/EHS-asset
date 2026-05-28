<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumable extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'supplier_id',
        'unit',
        'stock',
        'min_stock',
        'description',
    ];

    /**
     * Get the transactions for the consumable.
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConsumableTransaction::class);
    }

    /**
     * Get the category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the stock opnames for the consumable.
     */
    public function stockOpnames(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockOpname::class);
    }
}
