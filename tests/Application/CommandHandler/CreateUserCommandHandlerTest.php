<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Application\CommandHandler\CreateUserCommandHandler;
use App\Common\Exception\UserAlreadyExists;
use App\Domain\Builder\UserBuilder;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class CreateUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\UserAlreadyExists
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException|\App\Common\Exception\UserNotFoundException
     */
    public function testCreateUserCommand(User $user): void
    {
        $userBuilderMock = $this->getMockBuilder(UserBuilder::class)->onlyMethods(['build'])->disableOriginalConstructor()->getMock();

        $this->userRepositoryMock->expects($this->once())->method('findOneByEmail')->with('trader@journal.nl')->willReturn(null);
        $userBuilderMock->expects($this->once())->method('build')->with([
            'firstName' => 'Trader',
            'lastName' => 'Journal',
            'email' => 'trader@journal.nl',
            'password' => 'Password!@#',
            'capital' => 1000
        ])->willReturn($user);

        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new CreateUserCommandHandler($this->userRepositoryMock, $userBuilderMock, $this->loggerMock);
        $commandHandler(new CreateUserCommand('trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }

    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\UserAlreadyExists
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCreateUserCommandToThrowAnError(User $user): void
    {
        $userBuilderMock = $this->getMockBuilder(UserBuilder::class)->onlyMethods(['build'])->disableOriginalConstructor()->getMock();
        $this->userRepositoryMock->expects($this->once())->method('findOneByEmail')->with('trader@journal.nl')->willReturn($user);
        $this->loggerMock->expects($this->once())->method('error');

        $this->expectException(UserAlreadyExists::class);

        $commandHandler = new CreateUserCommandHandler($this->userRepositoryMock, $userBuilderMock, $this->loggerMock);
        $commandHandler(new CreateUserCommand('trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }
}
