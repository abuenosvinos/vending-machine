<?php

namespace App\Tests\Infrastructure\Persistence\InMemory;

use App\Domain\Product\Product;
use App\Domain\Product\ProductBox;
use App\Infrastructure\Persistence\InMemory\InMemoryProductBoxRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InMemoryProductBoxRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testStoreProductBoxEmpty()
    {
        $repository = new InMemoryProductBoxRepository(self::$kernel->getContainer());

        $productBox = ProductBox::fromRacks();
        $repository->store($productBox);

        $this->assertEquals($productBox, $repository->get());
    }

    public function testStoreProductBoxWithValues()
    {
        $repository = new InMemoryProductBoxRepository(self::$kernel->getContainer());

        $values = [
            ['name' => 'JUICE', 'price' => 0.65],
            ['name' => 'COKE', 'price' => 1.50],
            ['name' => 'COKE', 'price' => 1.50],
            ['name' => 'JUICE', 'price' => 0.65],
            ['name' => 'WATER', 'price' => 1.20],
            ['name' => 'WATER', 'price' => 1.20],
            ['name' => 'JUICE', 'price' => 0.65],
        ];

        $productBox = ProductBox::fromRacks();
        foreach ($values as $value) {
            $productBox->addProduct(Product::fromArray($value));
        }

        $repository->store($productBox);
        $this->assertEquals($productBox, $repository->get());
    }
}
