<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Domain\Product\Product;
use App\Shared\Domain\Bus\Command\Command;

final class ServiceSetQuantityProductCommand extends Command
{
    private Product $product;
    private int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
