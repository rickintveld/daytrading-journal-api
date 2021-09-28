<?php

namespace App\Application\Command;

use App\Common\Interfaces\Command;

/**
 * @package App\Application\Command
 */
class AddProfitCommand implements Command
{
    private int $userId;

    private float $profit;


    /**
     * @param int   $userId
     * @param float $profit
     */
    public function __construct(int $userId, float $profit)
    {
        $this->userId = $userId;
        $this->profit = $profit;
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
    public function getProfit(): float
    {
        return $this->profit;
    }
}
