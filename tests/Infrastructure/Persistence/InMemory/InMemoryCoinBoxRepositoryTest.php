<?php

namespace App\Tests\Infrastructure\Persistence\InMemory;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBox;
use App\Infrastructure\Persistence\InMemory\InMemoryCoinBoxRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InMemoryCoinBoxRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testStoreCoinBoxEmpty()
    {
        $repository = new InMemoryCoinBoxRepository(self::$kernel->getContainer());

        $coinBox = new CoinBox();
        $repository->store($coinBox);

        $this->assertEquals($coinBox, $repository->get());
    }

    public function testStoreCoinBoxWithValues()
    {
        $repository = new InMemoryCoinBoxRepository(self::$kernel->getContainer());

        $coinBox = new CoinBox();
        $values = ['0.05', '0.10', '0.5', '1'];
        foreach ($values as $value) {
            $coinBox->addCoin(new Coin($value));
        }

        $repository->store($coinBox);
        $this->assertEquals($coinBox, $repository->get());
    }
}
