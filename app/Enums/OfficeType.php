<?php

declare(strict_types=1);

namespace App\Enums;

enum OfficeType: string
{
    case Office = 'office';
    case RemoteHub = 'remote_hub';
    case Coworking = 'coworking';
    case Warehouse = 'warehouse';
}
