<?php

namespace App\Domain\Coin;

interface CoinUserRepository
{
    public function get(): CoinUser;

    public function store(CoinUser $coinUser): void;
}