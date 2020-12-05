<?php

namespace App\Tests\UI\Command\Operation;

use App\UI\Command\Operation\GetProductOperation;
use App\UI\Command\Operation\InsertCoinOperation;
use App\UI\Command\Operation\NotValidCoinException;
use App\UI\Command\Operation\ReturnCoinOperation;
use App\UI\Command\Operation\ServiceOperation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

class OperationTest extends KernelTestCase
{
    private $emptyContainer;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        parent::setUp();

        $this->emptyContainer = new Container();
    }

    public function testGetProductAttendValues()
    {
        $products = [
            ['name' => 'TEA', 'price' => 0.65],
            ['name' => 'COKE', 'price' => 1.25]
        ];
        $this->emptyContainer->setParameter('products', $products);
        $getProductOperation = new GetProductOperation($this->emptyContainer);

        $this->assertTrue($getProductOperation->attend('GET-TEA'));
        $this->assertTrue($getProductOperation->attend('GET-COKE'));
        $this->assertFalse($getProductOperation->attend('ABUENOSVINOS'));
    }

    public function testGetProductActionValues()
    {
        $products = [
            ['name' => 'TEA', 'price' => 0.65],
            ['name' => 'COKE', 'price' => 1.25]
        ];
        $this->emptyContainer->setParameter('products', $products);
        $getProductOperation = new GetProductOperation($this->emptyContainer);

        $this->assertEquals(['GET-TEA','GET-COKE'], $getProductOperation->actions());
    }

    public function testInsertCoinAttendValues()
    {
        $imports = [0.25, 0.65, 1.50];
        $this->emptyContainer->setParameter('imports', $imports);
        $insertCoinOperation = new InsertCoinOperation($this->emptyContainer);

        foreach ($imports as $import) {
            $this->assertTrue($insertCoinOperation->attend($import));
        }

        $this->assertFalse($insertCoinOperation->attend('abuenosvinos'));
    }

    public function testInsertCoinAttendException()
    {
        $this->expectException(NotValidCoinException::class);

        $insertCoinOperation = new InsertCoinOperation(self::$kernel->getContainer());

        $this->assertFalse($insertCoinOperation->attend('666'));
        $this->assertFalse($insertCoinOperation->attend('66.66'));
    }

    public function testReturnCoinAttendValues()
    {
        $returnCoinOperation = new ReturnCoinOperation();

        $this->assertTrue($returnCoinOperation->attend('RETURN-COIN'));
        $this->assertFalse($returnCoinOperation->attend('ABUENOSVINOS'));
    }

    public function testServiceOperationAttendValues()
    {
        $serviceOperation = new ServiceOperation();

        $this->assertTrue($serviceOperation->attend('SERVICE'));
        $this->assertFalse($serviceOperation->attend('ABUENOSVINOS'));
    }

}
