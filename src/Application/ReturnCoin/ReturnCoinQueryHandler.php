<?php

declare(strict_types = 1);

namespace App\Application\ReturnCoin;

use App\Shared\Domain\Bus\Query\QueryHandler;

final class ReturnCoinQueryHandler implements QueryHandler
{
    private $findCoinUser;

    public function __construct(FindCoinUser $findCoinUser)
    {
        $this->findCoinUser = $findCoinUser;
    }

    public function __invoke(ReturnCoinQuery $query)
    {
        return $this->findCoinUser->__invoke();
    }
}
