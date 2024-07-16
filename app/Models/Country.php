<?php

namespace App\Models;

use App\Services\Country\CountryService;
use App\Traits\ValidationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory, ValidationTrait;

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

    public function withdrawResources(Model $model)
    {
        $updatedResources = collect($this->resources)->mapWithKeys(function ($value, $resource) use ($model) {
            return [$resource => $value - ($model->resources_price[$resource] ?? 0)];
        });

        $this->validateWithdraw($updatedResources);

        $this->update([
            'resources' => $updatedResources,
        ]);
    }
}
