<?php

namespace App\Domain\Product;

class ProductRack
{
    private string $name;
    private float $price;
    private int $quantity;

    private function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = 0;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function addProduct(Product $product)
    {
        if (!$this->isSame($product)) {
            throw new ProductRackBadOperationException(sprintf('Not possible add Product %s on Rack %s', $product->name(), $this->name()));
        }

        $this->quantity++;
    }

    public function removeProduct(Product $product)
    {
        if ($this->quantity == 0) {
            throw new ProductRackBadOperationException(sprintf('Not possible remove Product %s on Rack %s, left %d', $product->name(), $this->name(), $this->quantity));
        }

        $this->quantity--;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function isSame(Product $product)
    {
        return ($this->name === $product->name() && $this->price === $product->price());
    }

    public static function fromArray(array $data): ProductRack
    {
        return new self(
            $data['name'],
            $data['price']
        );
    }

    public static function fromProduct(Product $product): ProductRack
    {
        return new self(
            $product->name(),
            $product->price()
        );
    }
}