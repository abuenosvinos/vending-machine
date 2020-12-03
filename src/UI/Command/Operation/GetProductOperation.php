<?php

namespace App\UI\Command\Operation;

use Psr\Container\ContainerInterface;

class GetProductOperation implements Operation
{
    private $products;

    public function __construct(ContainerInterface $container)
    {
        $this->products = $container->getParameter('products');
    }

    public function command(): string
    {
        return 'app:machine-vending-get-product';
    }

    public function actions(): array
    {
        $actions = [];
        foreach ($this->products as $product) {
            $actions[] = 'GET-' . $product['name'];
        }
        return $actions;
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
        $product = array_pop($input);
        return [
            'product' => $product,
            'coins' => $input
        ];
    }
}
