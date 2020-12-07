<?php

namespace App\Application\GetProduct;

use App\Shared\Domain\Bus\Event\Event;

class ProductWasSold extends Event
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}