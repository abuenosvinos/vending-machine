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
        $rack = $this->getRack($product);

        if ($rack == null) {
            $rack = ProductRack::fromProduct($product);
            $this->racks[] = $rack;
        }

        $rack->addProduct($product);
    }

    public function setQuantityOfProduct(Product $product, int $quantity)
    {
        /** @var ProductRack $rack */
        foreach ($this->racks as &$rack) {
            if ($rack->isSame($product)) {

                $rack = ProductRack::fromProduct($product);
                for ($i=0; $i<$quantity; $i++) {
                    $rack->addProduct($product);
                }
                return;
            }
        }

        $productRack = ProductRack::fromProduct($product);
        for ($i=0; $i<$quantity; $i++) {
            $productRack->addProduct($product);
        }

        $this->racks[] = $productRack;
    }

    public function racks(): array
    {
        return $this->racks;
    }

    public function getRack(Product $product): ?ProductRack
    {
        foreach ($this->racks as $rack) {
            if ($rack->isSame($product)) {
                return $rack;
            }
        }
        return null;
    }

    public static function fromRacks(array $racks = []): ProductBox
    {
        return new self($racks);
    }
}