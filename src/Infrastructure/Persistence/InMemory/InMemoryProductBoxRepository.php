<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Product\ProductBox;
use App\Domain\Product\ProductBoxRepository;

class InMemoryProductBoxRepository implements ProductBoxRepository
{
    private ProductBox $productBox;

    public function get(): ProductBox
    {
        return $this->productBox;
    }

    public function store(ProductBox $productBox): void
    {
        $this->productBox = $productBox;
    }
}