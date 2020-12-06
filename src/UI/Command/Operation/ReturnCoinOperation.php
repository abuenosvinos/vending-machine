<?php

namespace App\UI\Command\Operation;

class ReturnCoinOperation implements Operation
{
    public function command(): string
    {
        return 'app:machine-vending-return-coin';
    }

    public function actions(): array
    {
        return ['RETURN-COIN'];
    }

    public function attend(string $input): bool
    {
        return (in_array($input, $this->actions()));
    }

    public function params(string $input): array
    {
        return [];
    }
}
