<?php


namespace App\Tests\Application\GetProduct;

use App\Application\GetProduct\CheckProduct;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinUser;
use App\Domain\Product\Product;
use App\Domain\Product\ProductBox;
use App\Infrastructure\Persistence\InMemory\InMemoryCoinBoxRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCoinUserRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryProductBoxRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CheckProductTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();
    }

    /**
     * @dataProvider provideData
     */
    public function testCheckProduct($productToBuy, $dataProductBox, $dataCoinBox, $dataCoinsUser, $dataCoinsReturned)
    {
        list (
            $productToBuy,
            $productBoxRepository,
            $coinBoxRepository,
            $coinUserRepository,
            $dataCoinsReturned,
            ) = $this->populateData($productToBuy, $dataProductBox, $dataCoinBox, $dataCoinsUser, $dataCoinsReturned);

        $checkProduct = new CheckProduct(
            $productBoxRepository, $coinBoxRepository, $coinUserRepository
        );

        $productSold = $checkProduct->__invoke($productToBuy);

        $this->assertEquals($productSold->product(), $productToBuy);
        $this->assertEquals($productSold->coins(), $dataCoinsReturned);
    }

    public function populateData($productToBuy, $dataListProductBox, $dataListCoinBox, $dataListCoinsUser, $dataCoinsReturned): array
    {
        $productToBuy = Product::fromArray($productToBuy);

        $productBox = ProductBox::fromRacks();
        foreach ($dataListProductBox as $dataProductBox) {
            $productBox->addProduct(Product::fromArray($dataProductBox));
        }
        $productBoxRepository = new InMemoryProductBoxRepository();
        $productBoxRepository->store($productBox);

        $coinBox = CoinBox::fromRacks();
        foreach ($dataListCoinBox as $dataCoinBox) {
            $coinBox->addCoin(Coin::fromValue($dataCoinBox));
        }
        $coinBoxRepository = new InMemoryCoinBoxRepository();
        $coinBoxRepository->store($coinBox);

        $coinUser = CoinUser::fromCoins();
        foreach ($dataListCoinsUser as $dataCoinsUser) {
            $coinUser->addCoin(Coin::fromValue($dataCoinsUser));
        }
        $coinUserRepository = new InMemoryCoinUserRepository();
        $coinUserRepository->store($coinUser);

        $dataCoinsReturned = array_map(function($value) {
            return Coin::fromValue($value);
        }, $dataCoinsReturned);

        return [
            $productToBuy,
            $productBoxRepository,
            $coinBoxRepository,
            $coinUserRepository,
            $dataCoinsReturned
        ];
    }

    public function provideData(): array
    {
        return [
            [
                'productToBuy' => ['name' => 'JUICE', 'price' => 1.35],
                'productBox' => [['name' => 'JUICE', 'price' => 1.35]],
                'coinBox' => [1, 0.25, 0.10],
                'coinsUser' => [1, 0.25, 0.10],
                'coinsReturned' => []
            ],
            [
                'productToBuy' => ['name' => 'JUICE', 'price' => 1.35],
                'productBox' => [['name' => 'JUICE', 'price' => 1.35]],
                'coinBox' => [1, 0.25, 0.25, 0.10, 0.10, 0.05],
                'coinsUser' => [1, 1],
                'coinsReturned' => [0.05, 0.1, 0.25, 0.25]
            ],
            [
                'productToBuy' => ['name' => 'JUICE', 'price' => 1.35],
                'productBox' => [['name' => 'JUICE', 'price' => 1.35],['name' => 'SODA', 'price' => 0.75]],
                'coinBox' => [1, 0.25, 0.25, 0.10, 0.10, 0.05],
                'coinsUser' => [1, 1],
                'coinsReturned' => [0.05, 0.1, 0.25, 0.25]
            ],
        ];
    }
}
