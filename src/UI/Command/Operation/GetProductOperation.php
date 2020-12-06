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
        return (in_array($input, $this->actions()));
    }

    public function params(string $input): array
    {
        return [
            'product' => $input
        ];
    }
}
