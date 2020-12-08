<?php

namespace App\Application\ProductSold;

use App\Application\GetProduct\ProductWasSold;
use App\Domain\DataMiningRepository;
use App\Shared\Domain\Bus\Event\EventHandler;

class ProductSoldEventHandler implements EventHandler
{
    private DataMiningRepository $dataMiningRepository;

    public function __construct(DataMiningRepository $dataMiningRepository)
    {
        $this->dataMiningRepository = $dataMiningRepository;
    }

    public function productSold(ProductWasSold $productWasSold)
    {
        $this->dataMiningRepository->storeSale(
            $productWasSold->product(),
            $productWasSold
        );
    }

    public static function getHandledMessages(): iterable
    {
        yield ProductWasSold::class => [
            'method' => 'productSold',
        ];
    }

}
