<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait ValidationTrait
{
    public function validateWithdraw($updatedResources)
    {
        foreach ($updatedResources as $resource => $value) {
            if ($value < 0) {
                throw ValidationException::withMessages([
                    'message' => "Insufficient amount of {$resource}.",
                ]);
            }
        }
    }
}
