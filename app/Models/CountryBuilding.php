<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CountryBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'building_id',
        'count',
        'income_at'
    ];

}
