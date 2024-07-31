<?php

namespace App\Enums;

enum ProductTypeEnum: int
{
    case RESOURCE = 1;
    case BUILDING = 2;

    public static function resources(): array
    {
        return [
            self::cases(),
        ];
    }

    public function isResource(): bool
    {
        return $this === self::RESOURCE;
    }

    public function isBuilding(): bool
    {
        return $this === self::BUILDING;
    }
}
