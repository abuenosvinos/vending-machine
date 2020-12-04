<?php

namespace App\UI\Command\Operation;

use Psr\Container\ContainerInterface;

class InsertCoinOperation implements Operation
{
    private $imports;

    public function __construct(ContainerInterface $container)
    {
        $this->imports = $container->getParameter('imports');
    }

    public function command(): string
    {
        return 'app:machine-vending-insert-coin';
    }

    public function actions(): array
    {
        return $this->imports;
    }

    public function attend(string $input): bool
    {
        if (filter_var($input, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        $input = (float)$input;

        if (!in_array($input, $this->imports)) {
            throw new NotValidCoinException('The coin is not valid: ' . $input);
        }

        return true;
    }

    public function params(string $input): array
    {
        return [
            'coin' => $input
        ];
    }
}
