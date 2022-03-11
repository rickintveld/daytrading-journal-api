<?php

namespace App\Application\Command;

use App\Common\Contracts\Command;

class WithdrawCommand implements Command
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
