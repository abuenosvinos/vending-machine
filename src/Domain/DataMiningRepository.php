<?php

namespace App\Domain;

use App\Domain\Product\Product;
use App\Shared\Domain\Bus\Event\Event;

interface DataMiningRepository
{
    public function storeSale(Product $product, Event $event): void;
}