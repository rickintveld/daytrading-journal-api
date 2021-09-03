<?php

namespace App\Tests\Application\QueryHandler;

use App\Application\Query\AllUsersQuery;
use App\Application\QueryHandler\AllUserQueryHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\QueryHandler
 */
class AllUserQueryHandlerTest extends QueryHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function testAllUserQuery(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findAllActive')->with(false, false)->willReturn([$user]);

        $queryHandler = new AllUserQueryHandler($this->userRepositoryMock);

        $queryHandler(new AllUsersQuery());
    }

    /**
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function testAllUserQueryToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findAllActive')->with(false, false)->willReturn([]);

        $this->expectException(UserNotFoundException::class);

        $queryHandler = new AllUserQueryHandler($this->userRepositoryMock);

        $queryHandler(new AllUsersQuery());
    }
}
