<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Application\CommandHandler\BlockUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

class BlockUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testBlockUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new BlockUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new BlockUserCommand(1));
    }

    /**
     * @throws \Exception
     */
    public function testBlockUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new BlockUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new BlockUserCommand(1));
    }
}
