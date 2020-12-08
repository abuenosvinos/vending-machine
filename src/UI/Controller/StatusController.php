<?php

namespace App\UI\Controller;

use App\Application\Service\ServiceFindInventoryQuery;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinRack;
use App\Domain\Inventory;
use App\Domain\Product\ProductRack;
use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StatusController
{
    public function index(QueryBus $queryBus): JsonResponse
    {
        /** @var Inventory $inventory */
        $inventory = $queryBus->ask(new ServiceFindInventoryQuery());

        $status['products'] = array_map(function(ProductRack $productRack) {
            return [$productRack->name() => $productRack->quantity()];
        }, $inventory->productBox()->racks());

        $status['coins'] = array_map(function(CoinRack $coinRack) {
            return [(string)$coinRack->value() => $coinRack->quantity()];
        }, $inventory->coinBox()->racks());

        $status['credit'] = array_map(function(Coin $coin) {
            return [$coin->value()];
        }, $inventory->coinUser()->coins());

        return new JsonResponse(
            $status
        );
    }

    public function home(UrlGeneratorInterface $router): JsonResponse
    {
        return new JsonResponse(
            [
                'status' => $router->generate('status', [], UrlGenerator::ABSOLUTE_URL)
            ]
        );
    }
}