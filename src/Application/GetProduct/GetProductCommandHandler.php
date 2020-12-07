<?php

declare(strict_types = 1);

namespace App\Application\GetProduct;

use App\Shared\Domain\Bus\Command\CommandHandler;

final class GetProductCommandHandler implements CommandHandler
{
    private GetProduct $getProduct;

    public function __construct(GetProduct $getProduct)
    {
        $this->getProduct = $getProduct;
    }

    public function __invoke(GetProductCommand $command)
    {
        $this->getProduct->__invoke($command->product());
    }
}
