<?php

namespace App\DataFixtures;

use App\Infrastructure\Entity\Profit;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @package App\DataFixtures
 */
class TraderFixture extends Fixture
{
    /** @var UserPasswordHasherInterface */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $userSettings = new UserSettings();
        $userSettings->setCapital(40000);

        $profit = new Profit();
        $profit->setAmount(1500);

        $user = new User();
        $user->setEmail('test@trader.nl')
            ->setFirstName('Trading')
            ->setLastName('Journal')
            ->setUserSettings($userSettings)
            ->addProfit($profit);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, 'uAsmbaYK$6z=&75r')
        );

        $manager->persist($user);
        $manager->flush();
    }
}
