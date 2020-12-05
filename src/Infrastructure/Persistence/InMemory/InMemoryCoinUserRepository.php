<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Coin\CoinUser;
use App\Domain\Coin\CoinUserRepository;

class InMemoryCoinUserRepository implements CoinUserRepository
{
    private CoinUser $coinUser;

    public function get(): CoinUser
    {
        return $this->coinUser;
    }

    public function store(CoinUser $coinUser): void
    {
        $this->coinUser = $coinUser;
    }
}