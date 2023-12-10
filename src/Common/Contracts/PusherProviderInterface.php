<?php

namespace App\Common\Contracts;

use Pusher\Pusher;

interface PusherProviderInterface
{
    public function __invoke(): Pusher;
}
