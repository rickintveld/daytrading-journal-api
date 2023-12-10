<?php

namespace App\Domain\Pusher;

enum Event: string
{
    case NEW = 'new';
    case BLOCK = 'block';
    case UNBLOCK = 'unblock';
    case REMOVE = 'remove';
    case RESTORE = 'restore';
    case UPDATE = 'update';
    case WITHDRAW = 'withdraw';
}
