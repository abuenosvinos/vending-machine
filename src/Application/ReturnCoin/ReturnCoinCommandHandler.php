<?php

declare(strict_types = 1);

namespace App\Application\ReturnCoin;

use App\Shared\Domain\Bus\Command\CommandHandler;

final class ReturnCoinCommandHandler implements CommandHandler
{
    private EmptyCoinUser $emptyCoinUser;

    public function __construct(EmptyCoinUser $emptyCoinUser)
    {
        $this->emptyCoinUser = $emptyCoinUser;
    }

    public function __invoke(ReturnCoinCommand $command)
    {
        $this->emptyCoinUser->__invoke();
    }
}
