<?php

namespace App\Domain\Product;

class Product
{
    private string $name;
    private float $price;

    private function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public static function fromArray(array $data): Product
    {
        return new self(
            $data['name'],
            $data['price']
        );
    }
}