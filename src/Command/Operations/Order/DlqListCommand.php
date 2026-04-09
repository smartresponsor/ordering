<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Command\Operations\Order;

use App\CommandInterface\Console\Order\DlqListCommandInterface;
use App\ServiceInterface\Outbox\Order\DlqServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:dlq:list', description: 'List dead outbox messages (DLQ)')]
class DlqListCommand extends Command implements DlqListCommandInterface
{
    public function __construct(private readonly DlqServiceInterface $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this
            ->addOption('topic', null, InputOption::VALUE_OPTIONAL, 'Filter by topic')
            ->addOption('q', null, InputOption::VALUE_OPTIONAL, 'Search in payload/header')
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Page size', '50')
            ->addOption('offset', 'o', InputOption::VALUE_OPTIONAL, 'Offset', '0');
    }

    public function runCommand(InputInterface $input, OutputInterface $output): int
    {
        return $this->execute($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $topic = $input->getOption('topic') ?: null;
        $q = $input->getOption('q') ?: null;
        $limit = max(1, min(200, (int) $input->getOption('limit')));
        $offset = max(0, (int) $input->getOption('offset'));

        [$items, $total] = $this->service->getPage($topic, $q, $limit, $offset);

        $table = new Table($output);
        $table->setHeaders(['#', 'id', 'topic', 'attempt', 'status', 'occurredAt', 'reason']);
        $i = 0;
        foreach ($items as $m) {
            $table->addRow([
                $i++,
                method_exists($m, 'getId') ? (string) $m->getId() : '',
                method_exists($m, 'getTopic') ? (string) $m->getTopic() : '',
                method_exists($m, 'getAttempt') ? (string) $m->getAttempt() : '',
                method_exists($m, 'getStatus') ? (string) $m->getStatus() : '',
                (method_exists($m, 'getOccurredAt') && $m->getOccurredAt()) ? $m->getOccurredAt()->format('c') : '',
                method_exists($m, 'getHeader') ? (string) $m->getHeader() : '',
            ]);
        }
        $table->render();

        $output->writeln(sprintf('<info>Total:</info> %d  <comment>limit</comment>=%d <comment>offset</comment>=%d', $total, $limit, $offset));
        if ([] === $items && $offset > 0) {
            $output->writeln('<comment>Hint:</comment> try smaller offset or increase limit.');
        }

        return Command::SUCCESS;
    }
}
