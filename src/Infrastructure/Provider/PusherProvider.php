<?php

namespace App\Infrastructure\Provider;

use App\Common\Contracts\PusherProviderInterface;
use Pusher\Pusher;

final class PusherProvider implements PusherProviderInterface
{
    private static ?Pusher $pusher = null;

    public function __invoke(): Pusher
    {
        if (null === self::$pusher) {
            $options = ['cluster' => 'eu', 'useTLS' => true];

            self::$pusher = new Pusher(
                $_SERVER['PUSHER_KEY'],
                $_SERVER['PUSHER_SECRET'],
                $_SERVER['PUSHER_APP_ID'],
                $options
            );
        }

        return self::$pusher;
    }
}
