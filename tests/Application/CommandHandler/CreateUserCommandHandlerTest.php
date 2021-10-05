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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCreateUserCommand(User $user): void
    {
        $userBuilderMock = $this->getMockBuilder(UserBuilder::class)->onlyMethods(['build'])->disableOriginalConstructor()->getMock();

        $this->userRepositoryMock->expects($this->once())->method('findOneByEmail')->with('trader@journal.nl')->willReturn('');
        $userBuilderMock->expects($this->once())->method('build')->with([
            'firstName' => 'Trader',
            'lastName' => 'Journal',
            'email' => 'trader@journal.nl',
            'password' => 'Password!@#',
            'capital' => 1000
        ])->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new CreateUserCommandHandler($this->userRepositoryMock, $userBuilderMock);

        $commandHandler(new CreateUserCommand('trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }

    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \App\Common\Exception\UserAlreadyExists
     */
    public function testCreateUserCommandToThrowAnError(User $user): void
    {
        $userBuilderMock = $this->getMockBuilder(UserBuilder::class)->onlyMethods(['build'])->disableOriginalConstructor()->getMock();
        $this->userRepositoryMock->expects($this->once())->method('findOneByEmail')->with('trader@journal.nl')->willReturn($user);

        $this->expectException(UserAlreadyExists::class);

        $commandHandler = new CreateUserCommandHandler($this->userRepositoryMock, $userBuilderMock);

        $commandHandler(new CreateUserCommand('trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }
}
