<?php

namespace App\Tests\Application\QueryHandler;

use App\Application\Query\FindUserQuery;
use App\Application\QueryHandler\FindUserQueryHandler;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\QueryHandler
 */
class FindUserQueryHandlerTest extends QueryHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testFindUserQuery(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);

        $queryHandler = new FindUserQueryHandler($this->userRepositoryMock);

        $queryHandler(new FindUserQuery(1));
    }
}
