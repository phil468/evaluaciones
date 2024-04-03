<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;
	
    public $timestamps = true;

    protected $table = 'api_tokens';

    protected $fillable = [
        'user_id', 'access_token', 'token_type', 'expires_at', 'refresh_token'
    ];

    protected $dates = ['expires_at'];
}
