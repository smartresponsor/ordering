<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>
 * Owner: Marketing America Corp
 */

namespace App\Command\Order;

use App\CommandInterface\Console\Order\AuditRotateCommandInterface;
use App\ServiceInterface\Order\Http\AuditRotateInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:audit:rotate', description: 'Rotate & checksum audit NDJSON files')]
final class AuditRotateCommand extends Command implements AuditRotateCommandInterface
{
    public function __construct(private readonly AuditRotateInterface $rotator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('older-than-days', null, InputOption::VALUE_OPTIONAL, 'Rotate files older than N days (0 = before today)', '0');
        $this->addOption('no-gzip', null, InputOption::VALUE_NONE, 'Do not gzip rotated files');
    }

    public function runCommand(InputInterface $input, OutputInterface $output): int
    {
        return $this->execute($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $days = max(0, (int) $input->getOption('older-than-days'));
        $gzip = !$input->getOption('no-gzip');

        $result = $this->rotator->rotate($days, $gzip);

        $output->writeln(sprintf(
            '<info>Rotated:</info> %d  <info>Manifest:</info> %s  <info>SHA256:</info> %s',
            $result['rotated'],
            $result['manifest'],
            $result['checksum']
        ));

        return Command::SUCCESS;
    }
}
