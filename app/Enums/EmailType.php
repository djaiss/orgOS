<?php

declare(strict_types = 1);

namespace App\Enums;

enum EmailType: string
{
    case LOGIN_FAILED = 'login_failed';
    case USER_IP_CHANGED = 'user_ip_changed';
    case MAGIC_LINK_CREATED = 'magic_link_created';
}
