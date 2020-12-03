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
        $input = array_map('trim', explode(',', $input));
        $command = end($input);
        return (in_array($command, $this->actions()));
    }

    public function params(string $input): array
    {
        $input = array_map('trim', explode(',', $input));
        $command = array_pop($input);
        return [
            'coins' => $input
        ];
    }
}
