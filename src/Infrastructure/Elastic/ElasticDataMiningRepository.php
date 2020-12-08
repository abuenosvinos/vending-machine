<?php

namespace App\Infrastructure\Elastic;

use App\Domain\DataMiningRepository;
use App\Domain\Product\Product;
use App\Shared\Domain\Bus\Event\Event;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ElasticDataMiningRepository implements DataMiningRepository
{
    private Client $client;
    private bool $dataMiningEnabled = false;

    public function __construct(ParameterBagInterface $params)
    {
        $this->dataMiningEnabled = $params->get('dataMiningEnabled');
        if (!$this->dataMiningEnabled) {
            return;
        }

        $hosts = ['elasticsearch:9200'];

        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
        if (!$this->client->indices()->exists( ['index' => 'vending-machine'])) {
            $this->client->indices()->create([
                'index' => 'vending-machine',
                'body' => [
                    'mappings' => [
                        'properties' => [
                            'ocurredOn' => ['type' => 'date'],
                            'requestType' => ['type' => 'keyword'],
                            'requestId' => ['type' => 'keyword'],
                            'productName' => ['type' => 'keyword'],
                            'productPrice' => ['type' => 'float']
                        ]
                    ]
                ]
            ]);
        }
    }

    public function storeSale(Product $product, Event $event): void
    {
        if (!$this->dataMiningEnabled) {
            return;
        }

        $params = [
            'index' => 'vending-machine',
            'body'  => [
                'ocurredOn' => $event->occurredOn(),
                'requestType' => $event->requestType(),
                'requestId' => $event->requestId()->value(),
                'productName' => $product->name(),
                'productPrice' => $product->price()
            ]
        ];

        $this->client->index($params);
    }
}