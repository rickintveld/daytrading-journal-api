<?php

namespace App\Infrastructure\Builder;

use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserBuilder
 * @package App\Infrastructure\Builder
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
     * @param array<string, string|int> $arguments
     * @return \App\Infrastructure\Entity\User
     */
    public function build(array $arguments): User
    {
        $this->user
            ->setFirstName($arguments['firstName'])
            ->setLastName($arguments['lastName'])
            ->setEmail($arguments['email']);

        if (isset($arguments['capital'])) {
            $this->buildUserSettings($arguments['capital']);
        }
        if (isset($arguments['password'])) {
            $this->hashPassword($arguments['password']);
        }

        return $this->user;
    }

    /**
     * @param int $capital
     */
    private function buildUserSettings(int $capital): void
    {
        $userSettings = new UserSettings();
        $userSettings->setCapital($capital);

        $this->user->setUserSettings($userSettings);
    }

    /**
     * @param string $password
     */
    private function hashPassword(string $password): void
    {
        $this->user->setPassword(
            $this->passwordHasher->hashPassword($this->user, $password)
        );
    }
}
