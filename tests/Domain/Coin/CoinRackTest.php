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
        self::bootKernel();

        parent::setUp();
    }

    public function testCoinRack()
    {
        $value = 1.32;
        $coin = Coin::fromValue($value);
        $add = 7;
        $remove = 3;

        $coinRack = CoinRack::fromValue($value);

        for ($i=0; $i < $add; $i++) {
            $coinRack->addCoin($coin);
        }

        $this->assertTrue($coinRack->isSame($coin));
        $this->assertEquals($add, $coinRack->quantity());

        $coinRack->removeCoins($remove);
        $this->assertEquals($add - $remove, $coinRack->quantity());
    }

    public function testCoinRackBadOperationsByAdding()
    {
        $this->expectException(CoinRackBadOperationException::class);

        $value = 1.32;
        $differentValue = 6.66;

        $coinRack = CoinRack::fromValue($value);
        $coinRack->addCoin(Coin::fromValue($differentValue));
    }

    public function testCoinRackBadOperationsByRemoving()
    {
        $this->expectException(CoinRackBadOperationException::class);

        $value = 1.32;
        $add = 5;
        $remove = 6;

        $coinRack = CoinRack::fromValue($value);

        for ($i=0; $i < $add; $i++) {
            $coinRack->addCoin(Coin::fromValue($value));
        }

        $coinRack->removeCoins($remove);
    }

}
