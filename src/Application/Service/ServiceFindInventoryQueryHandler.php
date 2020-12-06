<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Shared\Domain\Bus\Query\QueryHandler;

final class ServiceFindInventoryQueryHandler implements QueryHandler
{
    private FindInventory $findInventory;

    public function __construct(FindInventory $findInventory)
    {
        $this->findInventory = $findInventory;
    }

    public function __invoke(ServiceFindInventoryQuery $query)
    {
        return $this->findInventory->__invoke();
    }
}
