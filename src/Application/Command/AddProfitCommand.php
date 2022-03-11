<?php

namespace App\Application\Command;

use App\Common\Contracts\Command;

class AddProfitCommand implements Command
{
    public function __construct(private string $userId, private float $profit)
    {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }
}
