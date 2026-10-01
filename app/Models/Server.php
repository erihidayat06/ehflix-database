<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name',
        'key',
        'type',
        'movie_url_pattern',
        'tv_url_pattern',
        'is_active',
        'sort_order',
    ];
}
