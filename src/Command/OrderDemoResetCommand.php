<?php

declare(strict_types=1);

namespace App\Ordering\Command;

use App\Ordering\Service\Demo\OrderDemoDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:order:demo:reset', description: 'Delete all demo order data')]
final class OrderDemoResetCommand extends Command
{
    public function __construct(private readonly OrderDemoDataService $demoDataService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->demoDataService->purge();
        $io->success('Demo order data removed.');

        return Command::SUCCESS;
    }
}
