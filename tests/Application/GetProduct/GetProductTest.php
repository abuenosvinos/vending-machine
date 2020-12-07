<?php


namespace App\Tests\Application\GetProduct;

use App\Application\GetProduct\GetProduct;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinUser;
use App\Domain\Product\Product;
use App\Domain\Product\ProductBox;
use App\Infrastructure\Persistence\InMemory\InMemoryCoinBoxRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCoinUserRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryProductBoxRepository;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetProductTest extends KernelTestCase
{
    /**
     * @var EventBus|MockObject
     */
    private $messageBus;

    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();

        $this->messageBus = $this->getMockBuilder(EventBus::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @dataProvider provideData
     */
    public function testCheckProduct($productToBuy, $dataProductBox, $dataCoinBox, $dataCoinsUser, $dataSumCoinBox)
    {
        list (
            $productToBuy,
            $productBoxRepository,
            $coinBoxRepository,
            $coinUserRepository,
            ) = $this->populateData($productToBuy, $dataProductBox, $dataCoinBox, $dataCoinsUser);

        $getProduct = new GetProduct(
            $productBoxRepository, $coinBoxRepository, $coinUserRepository, $this->messageBus
        );

        $getProduct->__invoke($productToBuy);

        /** @var CoinBox $coinBox */
        $coinBox = $coinBoxRepository->get();
        $sumCoinBox = array_reduce($coinBox->racks(),
            function($carry, $item) {
                $carry += $item->value() * $item->quantity();
                return $carry;
            }, 0
        );

        $this->assertEquals($sumCoinBox, $dataSumCoinBox);
    }

    public function populateData($productToBuy, $dataListProductBox, $dataListCoinBox, $dataListCoinsUser): array
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

        return [
            $productToBuy,
            $productBoxRepository,
            $coinBoxRepository,
            $coinUserRepository
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
                'sumCoinBox' => 2.7
            ],
            [
                'productToBuy' => ['name' => 'JUICE', 'price' => 1.35],
                'productBox' => [['name' => 'JUICE', 'price' => 1.35]],
                'coinBox' => [1, 0.25, 0.25, 0.10, 0.10, 0.05],
                'coinsUser' => [1, 1],
                'sumCoinBox' => 3.1
            ],
            [
                'productToBuy' => ['name' => 'JUICE', 'price' => 1.35],
                'productBox' => [['name' => 'JUICE', 'price' => 1.35],['name' => 'SODA', 'price' => 0.75]],
                'coinBox' => [1, 1, 1, 1, 0.5, 0.25, 0.25, 0.10, 0.10, 0.05, 0.05, 0.05, 0.05, 0.05],
                'coinsUser' => [1, 1],
                'sumCoinBox' => 6.8
            ],
        ];
    }
}
