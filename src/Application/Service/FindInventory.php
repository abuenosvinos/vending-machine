<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Domain\Coin\CoinBoxRepository;
use App\Domain\Coin\CoinUserRepository;
use App\Domain\Inventory;
use App\Domain\Product\ProductBoxRepository;

final class FindInventory
{
    private ProductBoxRepository $productBoxRepository;
    private CoinBoxRepository $coinBoxRepository;
    private CoinUserRepository $coinUserRepository;

    public function __construct(ProductBoxRepository $productBoxRepository, CoinBoxRepository $coinBoxRepository, CoinUserRepository $coinUserRepository)
    {
        $this->productBoxRepository = $productBoxRepository;
        $this->coinBoxRepository = $coinBoxRepository;
        $this->coinUserRepository = $coinUserRepository;
    }

    public function __invoke()
    {
        $productBox = $this->productBoxRepository->get();
        $coinBox = $this->coinBoxRepository->get();
        $coinUser = $this->coinUserRepository->get();

        return Inventory::fromData($productBox, $coinBox, $coinUser);
    }
}
