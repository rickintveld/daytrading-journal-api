<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\WithdrawCommand;
use App\Application\CommandHandler\WithdrawCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class WithdrawCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testWithdrawCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new WithdrawCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new WithdrawCommand(1, 1000));
    }

    /**
     * @throws \Exception
     */
    public function testWithdrawCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new WithdrawCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new WithdrawCommand(1, 1000));
    }
}
