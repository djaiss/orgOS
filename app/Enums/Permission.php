<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Member = 'member';
    case Guest = 'guest';
}
