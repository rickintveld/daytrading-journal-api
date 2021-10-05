<?php

namespace App\Domain\Builder;

use App\Domain\Model\User;
use Symfony\Component\Uid\Uuid;

/**
 * @package App\Infrastructure\Builder
 */
class UserBuilder implements Builder
{
    /**
     * @param array<string, string|int> $arguments
     * @return \App\Domain\Model\User
     * @throws \Exception
     */
    public function build(array $arguments): User
    {
        $user = new User(
            Uuid::v4(),
            $arguments['email'],
            $arguments['password'],
            $arguments['firstName'],
            $arguments['lastName'],
            $arguments['capital'],
        );

        return $user;
    }
}
