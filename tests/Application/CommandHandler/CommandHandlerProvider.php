<?php

namespace App\Tests\Application\CommandHandler;

use App\Domain\Model\Profit;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

/**
 * @package App\Tests\Application\CommandHandler
 */
abstract class CommandHandlerProvider extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $userRepositoryMock;

    /**
     * @return array
     * @throws \Exception
     */
    public function userProvider(): array
    {
        $profit = new Profit(1, 1000);

        $user = new User(1, 'trader@journal.nl', 'Password!@#', 'Trader', 'Journal', 40000, [$profit]);

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
            ['findOneByIdentifier', 'findOneByEmail', 'store']
        )->disableOriginalConstructor()->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepositoryMock);
    }
}
