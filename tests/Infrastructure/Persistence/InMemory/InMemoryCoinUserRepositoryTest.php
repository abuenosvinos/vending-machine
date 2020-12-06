<?php

namespace App\Tests\Infrastructure\Persistence\InMemory;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinUser;
use App\Infrastructure\Persistence\InMemory\InMemoryCoinUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InMemoryCoinUserRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    public function testStoreCoinUserEmpty()
    {
        $repository = new InMemoryCoinUserRepository(self::$kernel->getContainer());

        $coinUser = CoinUser::fromCoins();
        $repository->store($coinUser);

        $this->assertEquals($coinUser, $repository->get());
    }

    public function testStoreCoinUserWithValues()
    {
        $repository = new InMemoryCoinUserRepository(self::$kernel->getContainer());

        $coinUser = CoinUser::fromCoins();
        $values = ['0.05', '0.10', '0.5', '1'];
        foreach ($values as $value) {
            $coinUser->addCoin(Coin::fromValue($value));
        }

        $repository->store($coinUser);
        $this->assertEquals($coinUser, $repository->get());
    }
}
