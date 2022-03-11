<?php

namespace App\Infrastructure\Security\Provider;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginProvider
{
    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function login(UserInterface $user): void
    {
        $token = new UsernamePasswordToken($user, 'main');

        $this->tokenStorage->setToken($token);
    }

    public function getToken(): TokenInterface
    {
        return $this->tokenStorage->getToken();
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
    }
}
