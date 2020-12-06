<?php

namespace App\Tests\Domain\Product;

use App\Domain\Product\ProductRackBadOperationException;
use App\Domain\Product\Product;
use App\Domain\Product\ProductRack;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRackTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testProductRack()
    {
        $value = ['name' => 'JUICE', 'price' => 0.65];
        $productTest = Product::fromArray($value);
        $add = 7;
        $remove = 3;

        $productRack = ProductRack::fromArray($value);

        for ($i=0; $i < $add; $i++) {
            $productRack->addProduct($productTest);
        }

        $this->assertTrue($productRack->isSame($productTest));
        $this->assertEquals($add, $productRack->quantity());

        for ($i=0; $i < $remove; $i++) {
            $productRack->removeProduct($productTest);
        }
        $this->assertEquals($add - $remove, $productRack->quantity());
    }

    public function testProductRackBadOperationsByAdding()
    {
        $this->expectException(ProductRackBadOperationException::class);

        $value = ['name' => 'JUICE', 'price' => 0.65];
        $differentValue = ['name' => 'SODA', 'price' => 1.30];

        $productRack = ProductRack::fromArray($value);
        $productRack->addProduct(Product::fromArray($differentValue));
    }

    public function testProductRackBadOperationsByRemoving()
    {
        $this->expectException(ProductRackBadOperationException::class);

        $value = ['name' => 'JUICE', 'price' => 0.65];
        $add = 5;
        $remove = 6;

        $productRack = ProductRack::fromArray($value);

        for ($i=0; $i < $add; $i++) {
            $productRack->addProduct(Product::fromArray($value));
        }

        for ($i=0; $i < $remove; $i++) {
            $productRack->removeProduct(Product::fromArray($value));
        }
    }

}
