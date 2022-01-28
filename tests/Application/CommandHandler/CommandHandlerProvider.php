<?php

namespace App\Tests\Application\CommandHandler;

use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\Profit;
use App\Domain\Model\User;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @package App\Tests\Application\CommandHandler
 */
abstract class CommandHandlerProvider extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $userRepositoryMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $loggerMock;

    /**
     * @return array
     * @throws \Exception
     */
    public function userProvider(): array
    {
        $profit = new Profit(1, 1000);

        $user = new User(1, 'trader@journal.nl', 'Trader', 'Journal', 40000, 'Password!@#', [$profit]);

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
            ['findOneByIdentifier', 'findOneByEmail', 'store', 'findByEmailAndPassword', 'findAllActive']
        )->disableOriginalConstructor()->getMock();

        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
                                 ->onlyMethods(['error', 'emergency', 'critical', 'warning', 'notice', 'info', 'debug', 'log', 'alert'])
                                 ->disableOriginalConstructor()->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepositoryMock, $this->loggerMock);
    }
}
