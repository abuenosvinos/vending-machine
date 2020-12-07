<?php

declare(strict_types = 1);

namespace App\Application\GetProduct;

use App\Domain\Coin\CoinBoxRepository;
use App\Domain\Coin\CoinUser;
use App\Domain\Coin\CoinUserRepository;
use App\Domain\Product\Product;
use App\Domain\Product\ProductBoxRepository;
use App\Shared\Domain\Bus\Event\EventBus;

final class GetProduct extends CheckProduct
{
    private EventBus $bus;

    public function __construct(ProductBoxRepository $productBoxRepository, CoinBoxRepository $coinBoxRepository, CoinUserRepository $coinUserRepository, EventBus $bus)
    {
        parent::__construct($productBoxRepository, $coinBoxRepository, $coinUserRepository);

        $this->bus = $bus;
    }

    public function __invoke(Product $product)
    {
        $coinsToReturn = $this->checkMoneyBack($product);

        $this->removeProductFromProductBox($product);
        $this->updateCoinsFromCoinBox($coinsToReturn);
        $this->emptyCoinUser();

        $this->bus->notify(...[new ProductWasSold(
            [
                'product' => $product
            ]
        )]);
    }

    private function checkMoneyBack(Product $product): array
    {
        $coinBox = $this->coinBoxRepository->get();
        return $this->moneyBack($coinBox, $product);
    }

    private function removeProductFromProductBox(Product $product)
    {
        $productBox = $this->productBoxRepository->get();
        $productRack = $productBox->getRack($product);
        $productRack->removeProduct($product);
        $this->productBoxRepository->store($productBox);
    }

    private function updateCoinsFromCoinBox(array $coinsToReturn)
    {
        $coinBox = $this->coinBoxRepository->get();
        foreach ($coinsToReturn as $coin) {
            $coinRack = $coinBox->getRack($coin);
            $coinRack->removeCoins(1);
        }

        $coinUser = $this->coinUserRepository->get();
        foreach ($coinUser->coins() as $coin) {
            $coinRack = $coinBox->getRack($coin);
            $coinRack->addCoin($coin);
        }

        $this->coinBoxRepository->store($coinBox);
    }

    private function emptyCoinUser()
    {
        $coinUser = CoinUser::fromCoins();
        $this->coinUserRepository->store($coinUser);
    }
}
