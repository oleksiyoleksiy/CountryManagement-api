<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'resources_income',
        'resources_price',
        'cooldown',
    ];

    protected $casts = [
        'resources_income' => 'array',
        'resources_price' => 'array'
    ];
}
