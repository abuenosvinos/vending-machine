<?php

namespace App\Tests\Infrastructure\Persistence\FileSystem;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinUser;
use App\Infrastructure\Persistence\FileSystem\FileSystemCoinUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileSystemCoinUserRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();

        $container = self::$kernel->getContainer();

        $fileCoinUser = $container->getParameter('file_coin_user');
        if (file_exists($fileCoinUser)) {
            unlink($fileCoinUser);
        }
    }

    public function testStoreCoinUserEmpty()
    {
        $repository = new FileSystemCoinUserRepository(self::$kernel->getContainer());

        $coinUser = new CoinUser();
        $repository->store($coinUser);

        $this->assertEquals($coinUser, $repository->get());
    }

    public function testStoreCoinUserWithValues()
    {
        $repository = new FileSystemCoinUserRepository(self::$kernel->getContainer());

        $coinUser = new CoinUser();
        $values = ['0.05', '0.10', '0.5', '1'];
        foreach ($values as $value) {
            $coinUser->addCoin(new Coin($value));
        }

        $repository->store($coinUser);
        $this->assertEquals($coinUser, $repository->get());
    }
}
