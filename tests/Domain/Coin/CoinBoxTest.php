<?php

namespace App\Tests\Domain\Coin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinRack;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CoinBoxTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testCoinBox()
    {
        $values = [0.05, 0.05, 0.10, 0.5, 0.10, 0.05, 0.5, 1, 0.5];

        $coinBox = CoinBox::fromRacks();

        foreach ($values as $value) {
            $coinBox->addCoin(Coin::fromValue($value));
        }

        $racks = $coinBox->racks();
        $this->assertEquals(count(array_unique($values)), count($racks));

        $total = 0;

        /** @var CoinRack $rack */
        foreach ($racks as $rack) {
            $total += $rack->value() * $rack->quantity();
        }

        $this->assertEquals(array_sum($values), $total);
    }
}
