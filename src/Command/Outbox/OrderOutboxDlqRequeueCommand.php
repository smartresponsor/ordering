<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\Command\Outbox;

use App\Ordering\CommandInterface\Console\Order\OrderOutboxDlqRequeueCommandInterface;
use App\Ordering\ServiceInterface\Outbox\Order\DlqServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:outbox:dlq:requeue', description: 'Requeue dead outbox messages by ids or by filter page')]
class OrderOutboxDlqRequeueCommand extends Command implements OrderOutboxDlqRequeueCommandInterface
{
    public function __construct(private readonly DlqServiceInterface $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('id', InputArgument::IS_ARRAY, 'Message ULID(s) to requeue')
            ->addOption('reset-attempt', null, InputOption::VALUE_NONE, 'Reset attempt counter to 0 (default true)')
            ->addOption('keep-attempt', null, InputOption::VALUE_NONE, 'Do not reset attempt counter')
            ->addOption('topic', null, InputOption::VALUE_OPTIONAL, 'Filter topic for page requeue')
            ->addOption('q', null, InputOption::VALUE_OPTIONAL, 'Search filter for page requeue')
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Page size for page requeue', '50')
            ->addOption('offset', 'o', InputOption::VALUE_OPTIONAL, 'Offset for page requeue', '0')
            ->addOption('batch', 'b', InputOption::VALUE_NONE, 'Requeue current page instead of explicit ids');
    }

    public function runCommand(InputInterface $input, OutputInterface $output): int
    {
        return $this->execute($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ids = (array) $input->getArgument('id');
        $reset = !$input->getOption('keep-attempt'); // default true
        $count = 0;

        if ($input->getOption('batch') && !$ids) {
            $topic = $input->getOption('topic') ?: null;
            $q = $input->getOption('q') ?: null;
            $limit = max(1, min(200, (int) $input->getOption('limit')));
            $offset = max(0, (int) $input->getOption('offset'));
            [$items, $_] = $this->service->getPage($topic, $q, $limit, $offset);
            $ids = array_values(array_filter(array_map(fn ($m) => method_exists($m, 'getId') ? (string) $m->getId() : null, $items)));
        }

        if (!$ids) {
            $output->writeln('<error>No ids provided. Use arguments or --batch with filters.</error>');

            return Command::INVALID;
        }

        if (1 === count($ids)) {
            $ok = $this->service->requeueOne((string) $ids[0], $reset);
            $output->writeln($ok ? '<info>Requeued 1</info>' : '<comment>Not found</comment>');

            return $ok ? Command::SUCCESS : Command::FAILURE;
        }

        $count = $this->service->requeueMany(array_map('strval', $ids), $reset);
        $output->writeln(sprintf('<info>Requeued:</info> %d of %d', $count, count($ids)));

        return $count > 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
