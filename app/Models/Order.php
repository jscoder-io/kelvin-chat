<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'offer_id',
        'tab',
        'identifier',
        'customer',
        'contact',
        'address',
        'total',
        'data',
    ];

    protected $casts = [
        'total' => 'array',
        'data' => 'array',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    protected static function booted(): void
    {
        static::created(function (Order $order) {
            $query = $order->newModelQuery()->where('id', $order->id);
            $query->update([
                'updated_at' => $order->fromDateTime(Date::now()->subDays(10))
            ]);
        });

        static::updated(function (Order $order) {
            if ($order->isDirty('tab')) {
                $query = $order->newModelQuery()->where('id', $order->id);
                $query->update([
                    'updated_at' => $order->fromDateTime(Date::now()->subDays(10))
                ]);
            }
        });
    }
}
