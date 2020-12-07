<?php

namespace App\Domain;

use App\Domain\Product\Product;

class ProductSold
{
    private Product $product;
    private array $coins;

    private function __construct(Product $product, array $coins = [])
    {
        $this->product = $product;
        $this->coins = $coins;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function coins(): array
    {
        return $this->coins;
    }

    public static function fromData(Product $product, array $coins = []): ProductSold
    {
        return new self(
            $product,
            $coins
        );
    }
}