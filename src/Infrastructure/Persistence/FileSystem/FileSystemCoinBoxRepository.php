<?php

namespace App\Infrastructure\Persistence\FileSystem;

use App\Domain\Coin\CoinBox;
use App\Domain\Coin\CoinBoxRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileSystemCoinBoxRepository implements CoinBoxRepository
{
    private string $file;

    public function __construct(ContainerInterface $container)
    {
        $this->file = $container->getParameter('file_coin_box');
        if (!file_exists($this->file)) {
            touch($this->file);
        }
    }

    public function get(): CoinBox
    {
        $file = file_get_contents($this->file);
        $instance = unserialize($file);
        if (!($instance instanceof CoinBox)) {
            $instance = CoinBox::fromRacks();
        }
        return $instance;
    }

    public function store(CoinBox $coinBox): void
    {
        $content = serialize($coinBox);
        file_put_contents($this->file, $content);
    }
}