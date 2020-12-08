<?php

namespace App\Domain;

use App\Domain\Product\Product;

class ProductSold
{
    private Product $product;
    private array $coinsToReturn;

    private function __construct(Product $product, array $coinsToReturn = [])
    {
        $this->product = $product;
        $this->coinsToReturn = $coinsToReturn;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function coinsToReturn(): array
    {
        return $this->coinsToReturn;
    }

    public static function fromData(Product $product, array $coinsToReturn = []): ProductSold
    {
        return new self(
            $product,
            $coinsToReturn
        );
    }
}