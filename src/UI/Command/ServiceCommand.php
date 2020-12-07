<?php

namespace App\UI\Command;

use App\Application\Service\ServiceFindInventoryQuery;
use App\Application\Service\ServiceSetQuantityCoinCommand;
use App\Application\Service\ServiceSetQuantityProductCommand;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinRack;
use App\Domain\Inventory;
use App\Domain\Product\Product;
use App\Domain\Product\ProductRack;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServiceCommand extends Command
{
    protected static $defaultName = 'app:machine-vending-service';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private array $products;

    private array $imports;

    private CommandBus $commandBus;

    private QueryBus $queryBus;

    public function __construct(ContainerInterface $container, CommandBus $commandBus, QueryBus $queryBus)
    {
        parent::__construct();

        $this->products = $container->getParameter('products');
        $this->imports = $container->getParameter('imports');
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Service Operation')
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
        $this->io->section('Welcome to the service panel.');

        $this->showInformation();

        $this->askForInformation($input);

        $this->io->success('Thanks for your service, bye!');

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> perform the SERVICE operation
HELP;
    }

    private function showInformation()
    {
        $inventory = $this->queryBus->ask(new ServiceFindInventoryQuery());

        $this->showInformationProducts($inventory);
        $this->showInformationCoins($inventory);
        $this->showInformationCreditUser($inventory);
    }

    private function showInformationProducts(Inventory $inventory)
    {
        $productBox = $inventory->productBox();

        $products = array_reduce($productBox->racks(),
            function($carry, ProductRack $item) {
                $carry[] = [$item->name(), $item->quantity()];
                return $carry;
            }, []
        );

        $this->io->writeln('Inventory of products');
        if (count($products) > 0) {
            $this->io->table(
                ['Product', 'Quantity'],
                $products
            );
        } else {
            $this->io->writeln('Is Empty');
            $this->io->newLine();
        }
    }

    private function showInformationCoins(Inventory $inventory)
    {
        $coinBox = $inventory->coinBox();

        $coins = array_reduce($coinBox->racks(),
            function($carry, CoinRack $item) {
                $carry[] = [$item->value(), $item->quantity()];
                return $carry;
            }, []
        );

        $this->io->writeln('Inventory of coins');
        if (count($coins) > 0) {
            $this->io->table(
                ['Coin', 'Quantity'],
                $coins
            );
        } else {
            $this->io->writeln('Is Empty');
            $this->io->newLine();
        }
    }

    private function showInformationCreditUser(Inventory $inventory)
    {
        $coinUser = $inventory->coinUser();

        $coins = array_reduce($coinUser->coins(),
            function($carry, Coin $item) {
                $carry[] = [$item->value()];
                return $carry;
            }, []
        );

        $this->io->writeln('Credit User');
        if (count($coins) > 0) {
            $this->io->horizontalTable(
                ['Coin'],
                $coins
            );
        } else {
            $this->io->writeln('Is Empty');
            $this->io->newLine();
        }
    }

    private function askForInformation(InputInterface $input)
    {
        $this->askForProducts($input);
        $this->askForCoins($input);
    }

    private function askForProducts(InputInterface $input)
    {
        $helper = $this->getHelper('question');
        $question = new Question('> ');

        foreach ($this->products as $product) {

            do {
                $isEnd = false;
                $this->io->writeln(sprintf('Indicate the number of units of product %s', $product['name']));
                $answer = $helper->ask($input, $this->io, $question);
                if (is_numeric($answer)) {

                    $product = Product::fromArray($product);
                    $this->commandBus->dispatch(new ServiceSetQuantityProductCommand($product, $answer));

                    $isEnd = true;
                } else {
                    $this->io->warning(sprintf('It is not a valid quantity, please indicate it again'));
                }

            } while (!$isEnd);
        }
    }

    private function askForCoins(InputInterface $input)
    {
        $helper = $this->getHelper('question');
        $question = new Question('> ');

        foreach ($this->imports as $import) {
            do {
                $isEnd = false;
                $this->io->writeln(sprintf('Indicate the number of coins of %s', $import));
                $answer = $helper->ask($input, $this->io, $question);
                if (is_numeric($answer)) {

                    $coin = Coin::fromValue($import);
                    $this->commandBus->dispatch(new ServiceSetQuantityCoinCommand($coin, $answer));

                    $isEnd = true;
                } else {
                    $this->io->warning(sprintf('It is not a valid quantity, please indicate it again'));
                }

            } while (!$isEnd);
        }
    }
}
