<?php

declare(strict_types = 1);

namespace App\Application\InsertCoin;

use App\Shared\Domain\Bus\Command\CommandHandler;

final class InsertCoinCommandHandler implements CommandHandler
{
    private InsertCoin $insertCoin;

    public function __construct(InsertCoin $insertCoin)
    {
        $this->insertCoin = $insertCoin;
    }

    public function __invoke(InsertCoinCommand $command)
    {
        $coin = $command->coin();

        $this->insertCoin->__invoke($coin);
    }
}
