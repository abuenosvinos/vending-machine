<?php

declare(strict_types = 1);

namespace App\Application\GetProduct;

use App\Shared\Domain\Bus\Query\QueryHandler;

final class GetProductQueryHandler implements QueryHandler
{
    private CheckProduct $checkProduct;

    public function __construct(CheckProduct $checkProduct)
    {
        $this->checkProduct = $checkProduct;
    }

    public function __invoke(GetProductQuery $query)
    {
        return $this->checkProduct->__invoke($query->product());
    }
}
