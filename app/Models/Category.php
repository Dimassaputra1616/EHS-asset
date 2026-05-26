<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function assets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function consumables(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Consumable::class);
    }
}
