<?php

namespace App\Domain\Coin;

class Coin
{
    private float $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }
}