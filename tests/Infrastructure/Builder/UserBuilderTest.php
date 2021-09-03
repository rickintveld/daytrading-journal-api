<?php

namespace App\Tests\Infrastructure\Builder;

use App\Infrastructure\Builder\UserBuilder;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use PHPUnit\Framework\TestCase;

/**
 * Class UserBuilderTest
 * @package App\Tests\Infrastructure\Builder
 */
class UserBuilderTest extends TestCase
{
    /** @var User */
    private $user;

    public function testBuild(): void
    {
        $userBuilderMock = $this->createMock(UserBuilder::class);
        $userBuilderMock->method('build')->willReturn($this->user);

        $user = $userBuilderMock->build([
            'firstName' => 'Trading',
            'lastName' => 'Journal',
            'email' => 'journal@trading.nl',
            'password' => 'uAsmbaYK$6z=&75r',
            'capital' => 40000
        ]);

        self::assertEquals('uAsmbaYK$6z=&75r', $user->getPassword());
        self::assertNotNull($user->getUserSettings());
        self::assertEquals(40000, $user->getUserSettings()->getCapital());
        self::assertEquals('Trading', $user->getFirstName());
        self::assertEquals('Journal', $user->getLastName());
        self::assertEquals('journal@trading.nl', $user->getEmail());
    }

    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->setEmail('journal@trading.nl')
            ->setFirstName('Trading')
            ->setLastName('Journal')
            ->setPassword('uAsmbaYK$6z=&75r')
            ->setUserSettings((new UserSettings())->setCapital(40000));
    }

    protected function tearDown(): void
    {
        unset($this->user);
    }
}
