<?php

declare(strict_types = 1);

namespace App\Application\InsertCoin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinUserRepository;
use App\Shared\Domain\Bus\Event\EventBus;

final class InsertCoin
{
    private CoinUserRepository $repository;
    private EventBus $bus;

    public function __construct(CoinUserRepository $repository, EventBus $bus)
    {
        $this->repository = $repository;
        $this->bus        = $bus;
    }

    public function __invoke(Coin $coin)
    {
        $coinUser = $this->repository->get();
        $coinUser->addCoin($coin);
        $this->repository->store($coinUser);

        $this->bus->notify(...[new CoinWasInserted()]);
    }
}
