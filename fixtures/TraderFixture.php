<?php

namespace App\Fixtures;

use App\Domain\Builder\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @package App\DataFixtures
 */
class TraderFixture extends Fixture
{
    private $userBuilder;

    public function __construct(UserBuilder $userBuilder)
    {
        $this->userBuilder = $userBuilder;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->userBuilder->build([
            'firstName' => 'Trading',
            'lastName' => 'Journal',
            'email' => 'journal@trading.nl',
            'password' => 'uAsmbaYK$6z=&75r',
            'capital' => 40000,
        ]));
        $manager->flush();
    }
}
