<?php

namespace App\UI\Command;

use App\Application\ReturnCoin\ReturnCoinQuery;
use App\Domain\Coin\Coin;
use App\Domain\Coin\CoinUser;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\UI\Command\Operation\NotValidCoinException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InsertCoinCommand extends Command
{
    protected static $defaultName = 'app:machine-vending-insert-coin';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $imports;

    private CommandBus $commandBus;

    private QueryBus $queryBus;

    public function __construct(ContainerInterface $container, CommandBus $commandBus, QueryBus $queryBus)
    {
        parent::__construct();

        $this->imports = $container->getParameter('imports');
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Insert coin')
            ->addArgument('coin', InputArgument::IS_ARRAY, 'Coins to insert')
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
        $coin = $input->getArgument('coin');

        if (is_array($coin)) {
            $coin = $coin[0];
        }

        if (filter_var($coin, FILTER_VALIDATE_FLOAT) === false) {
            throw new NotValidCoinException(sprintf('The Coin %s is not valid. Only %s', $coin, implode(', ', $this->imports)));
        }

        $coin = (float)$coin;

        if (!in_array($coin, $this->imports)) {
            sort($this->imports);
            throw new NotValidCoinException(sprintf('The Coin %d is not valid. Only %s', $coin, implode(', ', $this->imports)));
        }

        $command = new \App\Application\InsertCoin\InsertCoinCommand(Coin::fromValue($coin));
        $this->commandBus->dispatch($command);

        /** @var CoinUser $coinUser */
        $coinUser = $this->queryBus->ask(new ReturnCoinQuery());

        $values = array_map(function(Coin $coin) {
            return $coin->value();
        }, $coinUser->coins());
        $credit = array_sum($values);

        $output->writeln(sprintf('Coin valid: Credit %s', $credit));

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> insert a coin into the vending machine
HELP;
    }
}
