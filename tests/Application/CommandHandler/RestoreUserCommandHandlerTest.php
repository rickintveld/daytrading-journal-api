<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\RestoreUserCommand;
use App\Application\CommandHandler\RestoreUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class RestoreUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testRemoveUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new RestoreUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new RestoreUserCommand(1));
    }

    /**
     * @throws \Exception
     */
    public function testRestoreUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new RestoreUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new RestoreUserCommand(1));
    }
}
