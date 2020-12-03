<?php

namespace App\UI\Command\Operation;

class ServiceOperation implements Operation
{
    public function command(): string
    {
        return 'app:machine-vending-service';
    }

    public function actions(): array
    {
        return ['SERVICE'];
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
