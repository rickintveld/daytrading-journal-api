<?php

namespace App\Domain\Model;

/**
 * @package App\Domain\Model
 */
class Profit
{
    /** @var int */
    private $id;

    /** @var float */
    private $amount;

    /**
     * @param int   $id
     * @param float $amount
     */
    public function __construct(int $id, float $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
