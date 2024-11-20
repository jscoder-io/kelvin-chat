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

    public function sortedToken()
    {
        $tokens = [
            'jwt-token' => new Token,
            '_csrf' => new Token,
            'csrf-token' => new Token,
            'session-key' => new Token
        ];
        foreach ($this->tokens as $token){
            $tokens[$token->key] = $token;
        }
        return $tokens;
    }

    public function isJwtTokenValid()
    {
        foreach ($this->tokens as $token) {
            if ($token->key == 'jwt-token' && $token->status == 'valid') {
                return true;
            }
        }
        return false;
    }
}
