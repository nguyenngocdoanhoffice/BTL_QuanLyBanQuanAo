<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['product_id', 'size', 'quantity', 'low_stock_threshold'])]
class Inventory extends Model
{
    use HasFactory;

    protected $casts = [
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(InventoryHistory::class);
    }
}
