<?php

namespace App\Domain\Coin;

class Coin
{
    private float $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public static function fromValue(float $value): Coin
    {
        return new self(
            $value
        );
    }
}