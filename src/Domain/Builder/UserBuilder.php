<?php

namespace App\Domain\Builder;

use App\Domain\Contracts\Builder\Builder;
use App\Domain\Model\User;
use Symfony\Component\Uid\Uuid;

class UserBuilder implements Builder
{
    /**
     * @throws \Exception
     */
    public function build(array $arguments): User
    {
        return new User(
            Uuid::v4()->toBinary(),
            $arguments['email'],
            $arguments['firstName'],
            $arguments['lastName'],
            $arguments['capital'],
            $arguments['password']
        );
    }
}
