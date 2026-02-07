<?php

namespace App\Command;

use App\GameElement\Core\GameComponent\AutoGameComponentSubscriber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'core:component:debug',
    description: 'Get a list of available components',
)]
class CoreComponentDebugCommand extends Command
{
    public function __construct(
        private AutoGameComponentSubscriber $engine,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $rows = [];
        foreach ($this->engine->getSubscribedComponents() as $component) {
            $rows[] = [
                'class' => $component,
            ];
        }
        $io->table(['class'], $rows);

        return Command::SUCCESS;
    }
}
