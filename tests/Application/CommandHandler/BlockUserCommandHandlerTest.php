<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Application\CommandHandler\BlockUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

class BlockUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testBlockUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new BlockUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new BlockUserCommand(1));
    }

    public function testBlockUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new BlockUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new BlockUserCommand(1));
    }
}
