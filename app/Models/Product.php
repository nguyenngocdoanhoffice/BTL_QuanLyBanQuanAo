<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'category_id',
    'brand_id',
    'title',
    'slug',
    'sku',
    'short_description',
    'description',
    'price',
    'sale_price',
    'is_trending',
    'is_new',
    'is_sale',
    'size_options',
    'cover_image',
    'status'
])]
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'is_trending' => 'boolean',
        'is_new' => 'boolean',
        'is_sale' => 'boolean',
        'size_options' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    protected function finalPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->sale_price) {
                    return $this->sale_price;
                }

                $discount = $this->discounts()
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                    })
                    ->first();

                if ($discount) {
                    return $discount->type === 'percentage'
                        ? $this->price - ($this->price * ($discount->value / 100))
                        : max($this->price - $discount->value, 0);
                }

                return $this->price;
            }
        );
    }
}
