<?php

namespace App\Tests\Infrastructure\Entity;

use App\Common\Exception\InvalidFundsException;
use App\Common\Exception\InvalidUserStateException;
use App\Domain\Model\Profit;
use App\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @package App\Tests\Infrastructure\Entity
 */
class UserTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function userProvider(): array
    {
        return [[
            new User(1, 'trader@journal.nl', 'Password!@#', 'Trading', 'Journal', 40000, [new Profit(1, 1000)])
        ]];
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testWithdrawToSucceed(User $user): void
    {
        $user->withdraw(31000);

        $this->assertEquals((float)10000, $user->getCapital());
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testWithdrawToThrowException(User $user): void
    {
        $this->expectException(InvalidFundsException::class);

        $user->withdraw(50000);
    }

    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function testBlock(User $user): void
    {
        $user->block();

        $this->assertTrue($user->isBlocked());
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function testBlockToThrowException(User $user): void
    {
        $user->setRemoved(true);
        $this->expectException(InvalidUserStateException::class);

        $user->block();
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function testUnblock(User $user): void
    {
        $user->unblock();

        $this->assertFalse($user->isBlocked());
    }
    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function testUnblockToThrowException(User $user): void
    {
        $user->setRemoved(true);

        $this->expectException(InvalidUserStateException::class);

        $user->unblock();
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function testRemove(User $user): void
    {
        $user->block();
        $user->remove();

        $this->assertTrue($user->isRemoved());
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function testRemoveToThrowException(User $user): void
    {
        $this->expectException(InvalidUserStateException::class);

        $user->remove();
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     */
    public function testRestore(User $user): void
    {
        $user->restore();

        $this->assertFalse($user->isBlocked());
        $this->assertFalse($user->isRemoved());
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     */
    public function testFullName(User $user): void
    {
        $this->assertEquals('Trading Journal', $user->getFullName());
    }

    /**
     * @dataProvider userProvider
     * @param \App\Domain\Model\User $user
     */
    public function testCapital(User $user): void
    {
        $this->assertEquals((float)41000, $user->getCapital());
    }
}
