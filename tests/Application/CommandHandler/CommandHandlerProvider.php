<?php

namespace App\Tests\Application\CommandHandler;

use App\Infrastructure\Entity\Profit;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @package App\Tests\Application\CommandHandler
 */
abstract class CommandHandlerProvider extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $userRepositoryMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $entityManagerMock;

    /**
     * @return array
     */
    public function userProvider(): array
    {
        $profit = new Profit();
        $profit->setAmount(1000);

        $userSetting = new UserSettings();
        $userSetting->setCapital(40000);

        $user = new User();
        $user->setFirstName('Trader')
             ->setLastName('Journal')
             ->setEmail('trader@journal.nl')
             ->setPassword('Password!@#')
             ->addProfit($profit)
             ->setUserSettings($userSetting);

        return [
            [
                $user,
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->onlyMethods(
            ['findOneBy']
        )->disableOriginalConstructor()->getMock();
        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)->onlyMethods(
            ['persist', 'flush']
        )->getMockForAbstractClass();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepositoryMock, $this->entityManagerMock);
    }
}
