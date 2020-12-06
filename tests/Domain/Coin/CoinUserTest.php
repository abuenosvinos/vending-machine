<?php

namespace App\Tests\Domain\Coin;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CoinUserTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testCoinUser()
    {
        $values = [0.05, 0.05, 0.10, 0.5, 0.10, 0.05, 0.5, 1, 0.5];

        $coinUser = CoinUser::fromCoins();

        foreach ($values as $value) {
            $coinUser->addCoin(Coin::fromValue($value));
        }

        $this->assertEquals(
            $values,
            array_map(function(Coin $coin) {
                return $coin->value();
            }, $coinUser->coins())
        );

        $coinUser->removeCoins();
        $this->assertEquals([], $coinUser->coins());
    }
}
