<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Shared\Domain\Bus\Command\CommandHandler;

final class ServiceSetQuantityCoinCommandHandler implements CommandHandler
{
    private SetQuantityCoin $setQuantityCoin;

    public function __construct(SetQuantityCoin $setQuantityCoin)
    {
        $this->setQuantityCoin = $setQuantityCoin;
    }

    public function __invoke(ServiceSetQuantityCoinCommand $command)
    {
        $this->setQuantityCoin->__invoke($command->coin(), $command->quantity());
    }
}
