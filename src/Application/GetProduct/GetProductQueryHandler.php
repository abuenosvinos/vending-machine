<?php

declare(strict_types = 1);

namespace App\Application\GetProduct;

use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetProductQueryHandler implements QueryHandler
{
    private GetProduct $getProduct;

    public function __construct(GetProduct $getProduct)
    {
        $this->getProduct = $getProduct;
    }

    public function __invoke(GetProductQuery $query)
    {
        return $this->getProduct->__invoke($query->product());
    }
}
