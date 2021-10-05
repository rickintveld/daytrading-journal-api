<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\RestoreUserCommand;
use App\Application\CommandHandler\RestoreUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class RestoreUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @throws \Exception
     */
    public function testRemoveUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new RestoreUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new RestoreUserCommand(1));
    }

    /**
     * @throws \Exception
     */
    public function testRestoreUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new RestoreUserCommandHandler($this->userRepositoryMock);

        $commandHandler(new RestoreUserCommand(1));
    }
}
