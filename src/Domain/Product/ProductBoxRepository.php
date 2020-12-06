<?php

namespace App\Domain\Product;

interface ProductBoxRepository
{
    public function get(): ProductBox;

    public function store(ProductBox $productBox): void;
}