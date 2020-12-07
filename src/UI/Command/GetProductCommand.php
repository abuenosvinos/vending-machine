<?php

namespace App\UI\Command;

use App\Application\GetProduct\GetProductQuery;
use App\Domain\Coin\Coin;
use App\Domain\Product\Product;
use App\Domain\ProductSold;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetProductCommand extends Command
{
    protected static $defaultName = 'app:machine-vending-get-product';

    private array $products;

    private CommandBus $commandBus;

    private QueryBus $queryBus;

    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(ContainerInterface $container, CommandBus $commandBus, QueryBus $queryBus)
    {
        parent::__construct();

        $this->products = $container->getParameter('products');
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Get Product of Vending Machine')
            ->addArgument('product', InputArgument::REQUIRED, 'Product to buy')
            ->setHelp($this->getCommandHelp())
        ;
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nameProduct = $input->getArgument('product');

        $productSold = null;
        foreach ($this->products as $product)
        {
            if ($product['name'] === $nameProduct) {
                $productToBuy = Product::fromArray($product);
                $productSold = $this->queryBus->ask(new GetProductQuery($productToBuy));
                $this->commandBus->dispatch(new \App\Application\GetProduct\GetProductCommand($productToBuy));
            }
        }

        if ($productSold instanceof ProductSold) {
            $response = [$productSold->product()->name()];
            $response = array_reduce($productSold->coins(),
                function($carry, Coin $item) {
                    $carry[] = $item->value();
                    return $carry;
                }, $$response
            );
            $this->io->writeln(implode(', ', $response));
        }

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> get the product selected
HELP;
    }
}
