<?php

declare(strict_types = 1);

namespace App\Application\GetProduct;

use App\Domain\Product\Product;
use App\Shared\Domain\Bus\Command\Command;

final class GetProductCommand extends Command
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
