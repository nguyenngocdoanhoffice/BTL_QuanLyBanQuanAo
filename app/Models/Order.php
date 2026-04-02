<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'code',
    'status',
    'payment_method',
    'payment_status',
    'subtotal',
    'discount_total',
    'shipping_fee',
    'total',
    'customer_name',
    'customer_email',
    'customer_phone',
    'shipping_address',
    'shipping_city',
    'shipping_postal_code',
    'notes'
])]
class Order extends Model
{
    use HasFactory;

    public const STATUS_PREPARING = 'preparing';
    public const STATUS_HANDED_OVER = 'handover';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_LABELS = [
        self::STATUS_PREPARING => 'Đang chuẩn bị',
        self::STATUS_HANDED_OVER => 'Đã bàn giao cho bên vận chuyển',
        self::STATUS_IN_TRANSIT => 'Đang vận chuyển',
        self::STATUS_COMPLETED => 'Đã hoàn thành',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected $appends = ['status_label'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statusOptions(): array
    {
        return self::STATUS_LABELS;
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status)),
        );
    }
}
