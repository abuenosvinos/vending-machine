<?php

namespace App\Domain\Coin;

class CoinUser
{
    private array $coins = [];

    private function __construct(array $coins = [])
    {
        $this->coins = $coins;
    }

    public function addCoin(Coin $coin)
    {
        $this->coins[] = $coin;
    }

    public function coins(): array
    {
        return $this->coins;
    }

    public function removeCoins()
    {
        $this->coins = [];
    }

    public function import(): float
    {
        return array_reduce($this->coins,
            function($carry, Coin $item) {
                $carry += $item->value();
                return $carry;
            }, 0
        );
    }

    public static function fromCoins(array $coins = []): CoinUser
    {
        return new self($coins);
    }
}