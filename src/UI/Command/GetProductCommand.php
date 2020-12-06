<?php

namespace App\UI\Command;

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

    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->products = $container->getParameter('products');
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
        $product = $input->getArgument('product');
        dump($product);

        $output->writeln('Command Get Product');

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> get the product selected
HELP;
    }
}
