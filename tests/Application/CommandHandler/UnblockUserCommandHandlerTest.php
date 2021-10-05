<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\UnBlockUserCommand;
use App\Application\CommandHandler\UnblockUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

class UnblockUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function testUnblockUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new UnblockUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new UnblockUserCommand(1));
    }

    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function testUnblockUserCommandWhileRemoved(User $user): void
    {
        $user->setRemoved(true);

        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);

        $this->expectException(\Exception::class);

        $commandHandler = new UnblockUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new UnblockUserCommand(1));
    }

    /**
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function testUnblockUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new UnblockUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new UnblockUserCommand(1));
    }
}
