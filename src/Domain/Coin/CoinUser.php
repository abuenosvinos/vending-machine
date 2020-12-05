<?php

namespace App\Domain\Coin;

class CoinUser
{
    private array $coins = [];

    public function __construct(array $coins = [])
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
}