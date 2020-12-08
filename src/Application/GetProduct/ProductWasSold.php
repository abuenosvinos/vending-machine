<?php

namespace App\Application\GetProduct;

use App\Domain\Product\Product;
use App\Shared\Domain\Bus\Event\Event;

class ProductWasSold extends Event
{
    private Product $product;

    public function __construct(Product $product)
    {
        parent::__construct();

        $this->product = $product;
    }

    public function product(): Product
    {
        return $this->product;
    }
}