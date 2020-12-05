<?php

declare(strict_types = 1);

namespace App\Application\ReturnCoin;

use App\Domain\Coin\CoinUserRepository;

final class FindCoinUser
{
    private CoinUserRepository $repository;

    public function __construct(CoinUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke()
    {
        return $this->repository->get();
    }
}
