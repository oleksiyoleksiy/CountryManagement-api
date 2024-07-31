<?php

namespace App\Models;

use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'resource',
        'country_id',
        'model_id',
        'count',
        'price'
    ];

    public function isResource(): bool
    {
        return ProductTypeEnum::from($this->type)->isResource();
    }

    public function isBuilding(): bool
    {
        return ProductTypeEnum::from($this->type)->isBuilding();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
