<?php

namespace App\Domain\Product;

class ProductBox
{
    private array $racks = [];

    private function __construct(array $racks = [])
    {
        $this->racks = $racks;
    }

    public function addProduct(Product $product)
    {
        /** @var ProductRack $rack */
        foreach ($this->racks as $rack) {
            if ($rack->isSame($product)) {
                $rack->addProduct($product);
                return;
            }
        }

        $productRack = ProductRack::fromProduct($product);
        $productRack->addProduct($product);

        $this->racks[] = $productRack;
    }

    public function racks(): array
    {
        return $this->racks;
    }

    public static function fromRacks(array $racks = [])
    {
        return new self($racks);
    }
}