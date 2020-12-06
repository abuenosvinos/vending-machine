<?php

namespace App\Infrastructure\Persistence\FileSystem;

use App\Domain\Product\ProductBox;
use App\Domain\Product\ProductBoxRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileSystemProductBoxRepository implements ProductBoxRepository
{
    private string $file;

    public function __construct(ContainerInterface $container)
    {
        $this->file = $container->getParameter('file_product_box');
        if (!file_exists($this->file)) {
            touch($this->file);
        }
    }

    public function get(): ProductBox
    {
        $file = file_get_contents($this->file);
        $instance = unserialize($file);
        if (!($instance instanceof ProductBox)) {
            $instance = ProductBox::fromRacks();
        }
        return $instance;
    }

    public function store(ProductBox $prodcutBox): void
    {
        $content = serialize($prodcutBox);
        file_put_contents($this->file, $content);
    }
}