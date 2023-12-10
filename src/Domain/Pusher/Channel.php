<?php

namespace App\Domain\Pusher;

enum Channel: string
{
    case PROFIT = 'profit';
    case USER = 'user';
}
