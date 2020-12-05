<?php

declare(strict_types = 1);

namespace App\Application\InsertCoin;

use App\Domain\Coin\Coin;
use App\Shared\Domain\Bus\Command\Command;

final class InsertCoinCommand extends Command
{
    private Coin $coin;

    public function __construct(Coin $coin)
    {
        $this->coin = $coin;
    }

    public function coin(): Coin
    {
        return $this->coin;
    }
}
