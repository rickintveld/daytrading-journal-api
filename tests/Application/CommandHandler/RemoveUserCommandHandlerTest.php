<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\RemoveUserCommand;
use App\Application\CommandHandler\RemoveUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

class RemoveUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testRemoveUserCommand(User $user): void
    {
        $user->block();

        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new RemoveUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new RemoveUserCommand(1));
    }

    /**
     * @dataProvider userProvider
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testRemoveUserCommandWhileNotBlocked(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);

        $this->expectException(\Exception::class);

        $commandHandler = new RemoveUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new RemoveUserCommand(1));
    }

    /**
     * @throws \Exception
     */
    public function testRemoveUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new RemoveUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new RemoveUserCommand(1));
    }
}
