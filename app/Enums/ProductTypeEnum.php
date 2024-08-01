<?php

namespace App\Enums;

enum ProductTypeEnum: int
{
    case FOSSIL = 1;

    public function isFossil(): bool
    {
        return $this === self::FOSSIL;
    }

}
