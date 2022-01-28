<?php

namespace App\Tests\Infrastructure\Builder;

use App\Domain\Builder\UserBuilder;
use App\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserBuilderTest
 * @package App\Tests\Infrastructure\Builder
 */
class UserBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $userMock = $this->createMock(User::class);
        $userMock->method('getPassword')->willReturn('uAsmbaYK$6z=&75r');
        $userMock->method('getStartCapital')->willReturn(10000.00);
        $userMock->method('getCapital')->willReturn(40000.00);
        $userMock->method('getFirstName')->willReturn('Trading');
        $userMock->method('getLastName')->willReturn('Journal');
        $userMock->method('getEmail')->willReturn('journal@trading.nl');

        $userBuilderMock = $this->createMock(UserBuilder::class);
        $userBuilderMock->method('build')->willReturn($userMock);

        $user = $userBuilderMock->build([
            'firstName' => 'Trading',
            'lastName' => 'Journal',
            'email' => 'journal@trading.nl',
            'password' => 'uAsmbaYK$6z=&75r',
            'capital' => 40000
        ]);

        self::assertEquals('uAsmbaYK$6z=&75r', $user->getPassword());
        self::assertNotNull($user->getStartCapital());
        self::assertEquals(40000.00, $user->getCapital());
        self::assertEquals('Trading', $user->getFirstName());
        self::assertEquals('Journal', $user->getLastName());
        self::assertEquals('journal@trading.nl', $user->getEmail());
    }
}
