<?php

namespace App\Domain\Model;

class Profit
{
    public function __construct(private string $userId, private float $amount)
    {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
