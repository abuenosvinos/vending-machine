<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Domain\Product\Product;
use App\Domain\Product\ProductBoxRepository;

final class SetQuantityProduct
{
    private ProductBoxRepository $productBoxRepository;

    public function __construct(ProductBoxRepository $productBoxRepository)
    {
        $this->productBoxRepository = $productBoxRepository;
    }

    public function __invoke(Product $product, int $quantity)
    {
        $productBox = $this->productBoxRepository->get();
        $productBox->setQuantityOfProduct($product, $quantity);

        $this->productBoxRepository->store($productBox);
    }
}
