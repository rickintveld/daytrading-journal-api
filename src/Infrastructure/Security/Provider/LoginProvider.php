<?php

namespace App\Infrastructure\Security\Provider;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginProvider
{
    private TokenStorageInterface $tokenStorage;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return void
     */
    public function login(UserInterface $user): void
    {
        $token = new UsernamePasswordToken($user, 'main');

        $this->tokenStorage->setToken($token);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function getToken(): TokenInterface
    {
        return $this->tokenStorage->getToken();
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
    }
}