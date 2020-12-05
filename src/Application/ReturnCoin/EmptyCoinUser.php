<?php

declare(strict_types = 1);

namespace App\Application\ReturnCoin;

use App\Domain\Coin\CoinUser;
use App\Domain\Coin\CoinUserRepository;

final class EmptyCoinUser
{
    private CoinUserRepository $repository;

    public function __construct(CoinUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke()
    {
        $this->repository->store(new CoinUser());
    }
}
