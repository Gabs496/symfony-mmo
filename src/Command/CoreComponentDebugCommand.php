<?php

namespace App\Command;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'core:component:debug',
    description: 'Get a list of available components',
)]
class CoreComponentDebugCommand extends Command
{
    public function __construct(
        /** @var iterable<GameComponentInterface> */
        private ParameterBagInterface $parameterBag,
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
        foreach ($this->parameterBag->get('game.components') as $id => $component) {
            $rows[] = [
                'id' => $id,
                'class' => $component,
            ];
        }
        $io->table(['id', 'class'], $rows);

        return Command::SUCCESS;
    }
}
