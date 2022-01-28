<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\RemoveUserCommand;
use App\Application\CommandHandler\RemoveUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

class RemoveUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     * @throws \Exception
     */
    public function testRemoveUserCommand(User $user): void
    {
        $user->block();

        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new RemoveUserCommandHandler($this->userRepositoryMock, $this->loggerMock);
        $commandHandler(new RemoveUserCommand('1'));
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testRemoveUserCommandWhileNotBlocked(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);

        $this->expectException(\Exception::class);

        $commandHandler = new RemoveUserCommandHandler($this->userRepositoryMock, $this->loggerMock);
        $commandHandler(new RemoveUserCommand('1'));
    }

    /**
     * @throws \Exception
     */
    public function testRemoveUserCommandToLogAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with('1')->willThrowException(new UserNotFoundException());
        $this->loggerMock->expects($this->once())->method('error');

        $commandHandler = new RemoveUserCommandHandler($this->userRepositoryMock, $this->loggerMock);
        $commandHandler(new RemoveUserCommand('1'));
    }
}
