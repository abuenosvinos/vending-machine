<?php

namespace App\Tests\Infrastructure\Persistence\FileSystem;

use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBox;
use App\Infrastructure\Persistence\FileSystem\FileSystemCoinBoxRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileSystemCoinBoxRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();

        $container = self::$kernel->getContainer();

        $fileCoinBox = $container->getParameter('file_coin_box');
        if (file_exists($fileCoinBox)) {
            unlink($fileCoinBox);
        }
    }

    public function testStoreCoinBoxEmpty()
    {
        $repository = new FileSystemCoinBoxRepository(self::$kernel->getContainer());

        $coinBox = new CoinBox();
        $repository->store($coinBox);

        $this->assertEquals($coinBox, $repository->get());
    }

    public function testStoreCoinBoxWithValues()
    {
        $repository = new FileSystemCoinBoxRepository(self::$kernel->getContainer());

        $coinBox = new CoinBox();
        $values = ['0.05', '0.10', '0.5', '1'];
        foreach ($values as $value) {
            $coinBox->addCoin(new Coin($value));
        }

        $repository->store($coinBox);
        $this->assertEquals($coinBox, $repository->get());
    }
}
