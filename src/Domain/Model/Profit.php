<?php

namespace App\Domain\Model;

class Profit
{
    private int $userId;
    private float $amount;

    /**
     * @param int   $userId
     * @param float $amount
     */
    public function __construct(int $userId, float $amount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getUserId(): int
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
