<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Demo\OrderDemoDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:order:demo:load', description: 'Reset and load demo order data')]
final class OrderDemoLoadCommand extends Command
{
    public function __construct(private readonly OrderDemoDataService $demoDataService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this->addOption('count', null, InputOption::VALUE_REQUIRED, 'Number of demo orders to generate', 12);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countOption = $input->getOption('count');
        $count = max(1, is_numeric($countOption) ? (int) $countOption : 12);

        $this->demoDataService->purge();
        $loaded = $this->demoDataService->load($count);

        $io->success(sprintf('Loaded %d demo orders.', $loaded));

        return Command::SUCCESS;
    }
}
