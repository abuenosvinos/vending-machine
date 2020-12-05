<?php

namespace App\Infrastructure\Persistence\FileSystem;

use App\Domain\Coin\CoinUser;
use App\Domain\Coin\CoinUserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileSystemCoinUserRepository implements CoinUserRepository
{
    private string $file;

    public function __construct(ContainerInterface $container)
    {
        $this->file = $container->getParameter('file_coin_user');
    }

    public function get(): CoinUser
    {
        $file = file_get_contents($this->file);
        return unserialize($file);
    }

    public function store(CoinUser $coinUser): void
    {
        $content = serialize($coinUser);
        file_put_contents($this->file, $content);
    }
}