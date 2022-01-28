<?php

namespace App\Domain\Model;

class Profit
{
    private string $userId;
    private float $amount;

    /**
     * @param string   $userId
     * @param float $amount
     */
    public function __construct(string $userId, float $amount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
