<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Shared\Domain\Bus\Command\CommandHandler;

final class ServiceSetQuantityProductCommandHandler implements CommandHandler
{
    private SetQuantityProduct $setQuantityProduct;

    public function __construct(SetQuantityProduct $setQuantityProduct)
    {
        $this->setQuantityProduct = $setQuantityProduct;
    }

    public function __invoke(ServiceSetQuantityProductCommand $command)
    {
        $this->setQuantityProduct->__invoke($command->product(), $command->quantity());
    }
}
