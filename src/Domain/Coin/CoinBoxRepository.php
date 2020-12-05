<?php

namespace App\Domain\Coin;

interface CoinBoxRepository
{
    public function get(): CoinBox;

    public function store(CoinBox $coinBox): void;
}