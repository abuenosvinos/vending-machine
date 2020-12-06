<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBoxRepository;

final class SetQuantityCoin
{
    private CoinBoxRepository $coinBoxRepository;

    public function __construct(CoinBoxRepository $coinBoxRepository)
    {
        $this->coinBoxRepository = $coinBoxRepository;
    }

    public function __invoke(Coin $coin, int $quantity)
    {
        $coinBox = $this->coinBoxRepository->get();
        $coinBox->setQuantityOfCoin($coin, $quantity);

        $this->coinBoxRepository->store($coinBox);
    }
}
