<?php

namespace App\Application\GetProduct;

use App\Domain\Product\Product;

class NotChangeAvailableException extends \RuntimeException
{
    public function __construct(Product $product)
    {
        parent::__construct(sprintf('There is not change available to buy the product %s.', $product->name()));
    }

}