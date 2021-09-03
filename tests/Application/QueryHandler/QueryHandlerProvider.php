<?php

namespace App\Tests\Application\QueryHandler;

use App\Domain\Model\Profit;
use App\Domain\Model\User;
use App\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

/**
 * @package App\Tests\Application\QueryHandler
 */
abstract class QueryHandlerProvider extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    protected $userRepositoryMock;

    /**
     * @return array
     * @throws \Exception
     */
    public function userProvider(): array
    {
        $user = new User(1, 'query@test.nl', 'Password!@#', 'Trader', 'Journal', 40000, [new Profit(1, 1000)]);

        return [
            [
                $user
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->onlyMethods(
            ['findAllActive', 'findOneByIdentifier']
        )->disableOriginalConstructor()->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepositoryMock);
    }
}
