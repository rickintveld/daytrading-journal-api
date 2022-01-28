<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\WithdrawCommand;
use App\Application\CommandHandler\WithdrawCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class WithdrawCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testWithdrawCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new WithdrawCommandHandler($this->userRepositoryMock, $this->loggerMock);

        $commandHandler(new WithdrawCommand('1', 1000));
    }

    /**
     * @throws \Exception
     */
    public function testWithdrawCommandToLogAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with('1')->willThrowException(new UserNotFoundException());
        $this->loggerMock->expects($this->once())->method('error');

        $commandHandler = new WithdrawCommandHandler($this->userRepositoryMock, $this->loggerMock);
        $commandHandler(new WithdrawCommand('1', 1000));
    }
}
