<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'marketplace',
    ];

    protected $casts = [
        //'token' => 'array',
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
