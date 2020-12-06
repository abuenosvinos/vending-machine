<?php

namespace App\Tests\Domain\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductBox;
use App\Domain\Product\ProductRack;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductBoxTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testProductBox()
    {
        $values = [
            ['name' => 'JUICE', 'price' => 0.65],
            ['name' => 'COKE', 'price' => 1.50],
            ['name' => 'COKE', 'price' => 1.50],
            ['name' => 'JUICE', 'price' => 0.65],
            ['name' => 'WATER', 'price' => 1.20],
            ['name' => 'WATER', 'price' => 1.20],
            ['name' => 'JUICE', 'price' => 0.65],
        ];

        $coinBox = ProductBox::fromRacks();

        foreach ($values as $value) {
            $coinBox->addProduct(Product::fromArray($value));
        }

        $racks = $coinBox->racks();

        $uniqueValues = array_reduce($values,
            function($carry, $item) {
                if (!in_array($item['name'], $carry)) {
                    $carry[] = $item['name'];
                }
                return $carry;
            }, []
        );

        $uniqueProducts = array_reduce($racks,
            function($carry, ProductRack $item) {
                $carry[] = $item->name();
                return $carry;
            }, []
        );

        $this->assertEquals($uniqueValues, $uniqueProducts);

        $sumValues = array_reduce($values,
            function($carry, $item) {
                $carry += $item['price'];
                return $carry;
            }, 0
        );

        $sumProducts = array_reduce($racks,
            function($carry, ProductRack $item) {
                $carry += ($item->price() * $item->quantity());
                return $carry;
            }, 0
        );

        $this->assertEquals($sumValues, $sumProducts);
    }
}
