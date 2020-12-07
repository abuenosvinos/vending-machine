<?php

namespace App\Application\GetProduct;

use App\Domain\Product\Product;

class NotEnoughMoneyException extends \RuntimeException
{
    public function __construct(Product $product, float $left)
    {
        parent::__construct(sprintf('There is not enough money to buy product %s. %s cents left.', $product->name(), $left));
    }

}