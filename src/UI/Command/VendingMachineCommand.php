<?php

namespace App\UI\Command;

use App\UI\Command\Operation\GetProductOperation;
use App\UI\Command\Operation\InsertCoinOperation;
use App\UI\Command\Operation\Operation;
use App\UI\Command\Operation\OperationNotAvailableException;
use App\UI\Command\Operation\ReturnCoinOperation;
use App\UI\Command\Operation\ServiceOperation;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'app:machine-vending-start';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $operations = [];

    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Vending Machine Service')
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

        $this->operations[] = new ServiceOperation();
        $this->operations[] = new GetProductOperation($this->container);
        $this->operations[] = new ReturnCoinOperation();
        $this->operations[] = new InsertCoinOperation($this->container);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $actions = ['EXIT'];
        /** @var Operation $operation */
        foreach ($this->operations as $operation) {
            $actions = array_merge($actions, $operation->actions());
        }

        $question = new Question('> ');
        $question->setAutocompleterValues($actions);

        $helper = $this->getHelper('question');
        do {

            try {
                $answer = $helper->ask($input, $output, $question);

                if ($answer === null) {
                    throw new OperationNotAvailableException();
                }

                $attended = false;

                /** @var Operation $operation */
                foreach ($this->operations as $operation) {

                    if ($operation->attend($answer)) {

                        $input = new ArrayInput($operation->params($answer));

                        $command = $this->getApplication()->find($operation->command());
                        $returnCode = $command->run($input, $output);

                        $attended = true;
                        break;
                    }
                }

                if (!$attended && $this->isFinish($answer)) {
                    throw new OperationNotAvailableException();
                }

            } catch (\Throwable $e) {
                $output->writeln($e->getMessage());
            }

        } while ($this->isFinish($answer));

        return Command::SUCCESS;
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command starts the vending machine service
HELP;
    }

    private function isFinish(string $input = null): bool {
        return ($input !== 'EXIT');
    }
}
