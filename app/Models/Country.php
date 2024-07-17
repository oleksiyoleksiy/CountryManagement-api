<?php

namespace App\Models;

use App\Enums\ResourceEnum;
use App\Services\Country\CountryService;
use App\Traits\CountryOperationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory, CountryOperationTrait;

    protected $fillable = [
        'name',
        'user_id',
        'resources',
        'available_resources',
    ];

    protected $casts = [
        'resources' => 'array',
        'available_resources' => 'array',
    ];

    public function buildings(): BelongsToMany
    {
        return $this->belongsToMany(Building::class)->withPivot(['count', 'income_at']);
    }
}
