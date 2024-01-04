<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'chat_id',
        'buyer_id',
        'shop_id',
        'username',
        'profile_image',
        'product_title',
        'product_image',
        'price_formatted',
        'product_url',
        'channel_url',
        'latest_message',
        'unread_count',
        'data',
        'latest_created',
    ];

    protected $casts = [
        'data' => 'array',
        'latest_created' => 'datetime',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
