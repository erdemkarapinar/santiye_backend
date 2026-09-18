<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gorevler extends Model
{
    protected $fillable = [
        'user_id',
        'santiye_id',
        'title',
        'description',
    ];
}
