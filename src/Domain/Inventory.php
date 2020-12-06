<?php

namespace App\Domain;

use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinUser;
use App\Domain\Product\ProductBox;

class Inventory
{
    private ProductBox $productBox;
    private CoinBox $coinBox;
    private CoinUser $coinUser;

    private function __construct(ProductBox $productBox, CoinBox $coinBox, CoinUser $coinUser)
    {
        $this->productBox = $productBox;
        $this->coinBox = $coinBox;
        $this->coinUser = $coinUser;
    }

    public function productBox(): ProductBox
    {
        return $this->productBox;
    }

    public function coinBox(): CoinBox
    {
        return $this->coinBox;
    }

    public function coinUser(): CoinUser
    {
        return $this->coinUser;
    }

    public static function fromData(ProductBox $productBox, CoinBox $coinBox, CoinUser $coinUser): Inventory
    {
        return new self(
            $productBox,
            $coinBox,
            $coinUser
        );
    }
}