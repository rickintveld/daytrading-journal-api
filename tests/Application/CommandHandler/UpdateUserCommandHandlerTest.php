<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\UpdateUserCommand;
use App\Application\CommandHandler\UpdateUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class UpdateUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testUpdateUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new UpdateUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new UpdateUserCommand(1, 'trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function testUpdateUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new UpdateUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new UpdateUserCommand(1, 'trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }
}
