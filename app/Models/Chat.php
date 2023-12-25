<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chat';

    protected $fillable = [
        'message_id',
        'chat_id',
        'message',
        'type',
        'custom_type',
        'user',
        'data',
        'file',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'file' => 'array',
        'created_at' => 'datetime',
    ];
}
