<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\UpdateUserCommand;
use App\Application\CommandHandler\UpdateUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class UpdateUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testUpdateUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new UpdateUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new UpdateUserCommand(1, 'trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testUpdateUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new UpdateUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new UpdateUserCommand(1, 'trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }
}
