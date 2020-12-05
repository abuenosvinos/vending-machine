<?php

namespace App\Tests\Domain\Coin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinRack;
use App\Domain\Coin\CoinRackBadOperationException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CoinRackTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'test']);

        parent::setUp();
    }

    public function testCoinRack()
    {
        $value = 1.32;
        $add = 7;
        $remove = 3;

        $coinRack = new CoinRack($value);

        for ($i=0; $i < $add; $i++) {
            $coinRack->addCoin(new Coin($value));
        }

        $this->assertTrue($coinRack->isSame($value));
        $this->assertEquals($add, $coinRack->quantity());

        $coinRack->removeCoins($remove);
        $this->assertEquals($add - $remove, $coinRack->quantity());
    }

    public function testCoinRackBadOperationsByAdding()
    {
        $this->expectException(CoinRackBadOperationException::class);

        $value = 1.32;
        $differentValue = 6.66;

        $coinRack = new CoinRack($value);
        $coinRack->addCoin(new Coin($differentValue));
    }

    public function testCoinRackBadOperationsByRemoving()
    {
        $this->expectException(CoinRackBadOperationException::class);

        $value = 1.32;
        $add = 5;
        $remove = 6;

        $coinRack = new CoinRack($value);

        for ($i=0; $i < $add; $i++) {
            $coinRack->addCoin(new Coin($value));
        }

        $coinRack->removeCoins($remove);
    }

}
