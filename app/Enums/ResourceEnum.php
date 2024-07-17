<?php

namespace App\Enums;

enum ResourceEnum: string
{
    case MONEY = 'money';
    case ENERGY = 'energy';
    case COAL = 'coal';
    case URANIUM = 'uranium';
    case IRON = 'iron';
    case COPPER = 'copper';
    case OIL = 'oil';
}
