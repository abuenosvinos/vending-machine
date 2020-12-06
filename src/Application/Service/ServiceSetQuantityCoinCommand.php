<?php

declare(strict_types = 1);

namespace App\Application\Service;

use App\Domain\Coin\Coin;
use App\Shared\Domain\Bus\Command\Command;

final class ServiceSetQuantityCoinCommand extends Command
{
    private Coin $coin;
    private int $quantity;

    public function __construct(Coin $coin, int $quantity)
    {
        parent::__construct();

        $this->coin = $coin;
        $this->quantity = $quantity;
    }

    public function coin(): Coin
    {
        return $this->coin;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
