<?php

namespace App\Infrastructure\Infrastructure\Builder;

use App\Infrastructure\Builder\Builder;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserBuilder
 * @package App\Infrastructure\Infrastructure\Builder
 */
class UserBuilder implements Builder
{
    /** @var User */
    private $user;

    /** @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface */
    private $passwordHasher;

    /**
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->user = new User();
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param array $arguments
     */
    public function build(array $arguments): void
    {
        $this->user
            ->setFirstName($arguments['firstName'])
            ->setLastName($arguments['lastName'])
            ->setEmail($arguments['email']);

        $this->buildUserSettings($arguments);
        $this->hashPassword($arguments);
    }

    /**
     * @return \App\Infrastructure\Entity\User
     */
    public function get(): User
    {
        return $this->user;
    }

    /**
     * @param array $arguments
     */
    private function buildUserSettings(array $arguments): void
    {
        $userSettings = new UserSettings();
        $userSettings->setCapital($arguments['capital'] ?: 0);

        $this->user->setUserSettings($userSettings);
    }

    /**
     * @param array $arguments
     */
    private function hashPassword(array $arguments): void
    {
        $this->user->setPassword(
            $this->passwordHasher->hashPassword($this->user, $arguments['password'])
        );
    }
}
