<?php

declare(strict_types = 1);

namespace App\Application\GetProduct;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinBoxRepository;
use App\Domain\Coin\CoinRack;
use App\Domain\Coin\CoinUserRepository;
use App\Domain\Product\Product;
use App\Domain\Product\ProductBoxRepository;
use App\Domain\ProductSold;

class CheckProduct
{
    protected ProductBoxRepository $productBoxRepository;
    protected CoinBoxRepository $coinBoxRepository;
    protected CoinUserRepository $coinUserRepository;

    public function __construct(ProductBoxRepository $productBoxRepository, CoinBoxRepository $coinBoxRepository, CoinUserRepository $coinUserRepository)
    {
        $this->productBoxRepository = $productBoxRepository;
        $this->coinBoxRepository = $coinBoxRepository;
        $this->coinUserRepository = $coinUserRepository;
    }

    public function __invoke(Product $product)
    {
        $coinBox = $this->coinBoxRepository->get();
        $coinsToReturn = $this->moneyBack($coinBox, $product);

        return ProductSold::fromData($product, $coinsToReturn);
    }

    protected function moneyBack(CoinBox $coinBox, Product $product): array
    {
        $coinUser = $this->coinUserRepository->get();
        $importUser = $coinUser->import();

        if ($importUser < $product->price()) {
            throw new NotEnoughMoneyException($product, $product->price() - $importUser);
        }

        $racks = $coinBox->racks();
        usort($racks, function($a, $b) { return ($a->value() < $b->value()); });

        $importToReturnUser = abs(round($product->price() - $importUser, 2));
        if ($importToReturnUser == 0) {
            return [];
        }

        $coinsToReturn = $this->processExchange($racks, $importToReturnUser);

        if (count($coinsToReturn) === 0) {
            throw new NotChangeAvailableException($product);
        }

        return $coinsToReturn;
    }

    private function processExchange(array $racks, float $import)
    {
        $coinsToReturn = [];

        /** @var CoinRack $rack */
        foreach ($racks as $index => $rack) {
            if ($rack->quantity() === 0) continue;

            $coinsNeeded = (int)floor($import / $rack->value());

            $max = min($coinsNeeded, $rack->quantity());
            for ($i=$max; $i>0; $i--) {
                $partialImport = round($import - ($rack->value() * $i), 2);
                if ($partialImport == 0) {
                    return $this->putCoinsToReturn($coinsToReturn, $rack->value(), $i);
                }

                unset($racks[$index]);
                $partialResponse = $this->processExchange($racks, $partialImport);
                if (count($partialResponse) > 0) {
                    return $this->putCoinsToReturn($partialResponse, $rack->value(), $i);
                }
            }
        }

        return $coinsToReturn;
    }

    private function putCoinsToReturn(array $coinsToReturn, $value, $times) {
        for ($i=0; $i<$times; $i++) {
            $coinsToReturn[] = Coin::fromValue($value);
        }
        return $coinsToReturn;
    }
}
