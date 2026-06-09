<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'attribute_product')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}