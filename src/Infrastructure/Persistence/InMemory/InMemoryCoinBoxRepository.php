<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinBoxRepository;

class InMemoryCoinBoxRepository implements CoinBoxRepository
{
    private CoinBox $coinBox;

    public function get(): CoinBox
    {
        return $this->coinBox;
    }

    public function store(CoinBox $coinBox): void
    {
        $this->coinBox = $coinBox;
    }
}