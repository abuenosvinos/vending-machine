<?php

namespace App\Domain\Coin;

class CoinRack
{
    private float $value;
    private int $quantity;

    private function __construct(string $value)
    {
        $this->value = $value;
        $this->quantity = 0;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function addCoin(Coin $coin)
    {
        if (!$this->isSame($coin->value())) {
            throw new CoinRackBadOperationException(sprintf('Not possible add Coin %d on Rack %d', $coin->value(), $this->value()));
        }

        $this->quantity++;
    }

    public function removeCoins(int $quantity)
    {
        if ($this->quantity < $quantity) {
            throw new CoinRackBadOperationException(sprintf('Not possible remove %d Coins on Rack %d, left %d', $quantity, $this->value(), $this->quantity));
        }

        $this->quantity -= $quantity;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function isSame(float $value)
    {
        return ($this->value === $value);
    }

    public static function fromValue(string $value): CoinRack
    {
        return new self(
            $value
        );
    }
}