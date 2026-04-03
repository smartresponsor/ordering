<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>
 * Owner: Marketing America Corp
 */

namespace App\Command\Order;

use App\CommandInterface\Console\Order\AuditShipS3CommandInterface;
use App\ServiceInterface\Order\Http\AuditShipInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:audit:ship-s3', description: 'Upload audit NDJSON (.gz) to S3 with SSE (AES256/KMS)')]
final class AuditShipS3Command extends Command implements AuditShipS3CommandInterface
{
    public function __construct(private readonly AuditShipInterface $shipper)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'YYYY-MM-DD or "today"', 'today')
            ->addOption('bucket', null, InputOption::VALUE_OPTIONAL, 'Override S3 bucket')
            ->addOption('prefix', null, InputOption::VALUE_OPTIONAL, 'Object key prefix (default order/)')
            ->addOption('sse', null, InputOption::VALUE_OPTIONAL, 'SSE: AES256 or aws:kms')
            ->addOption('kms-key', null, InputOption::VALUE_OPTIONAL, 'KMS key id/arn for aws:kms');
    }

    public function runCommand(InputInterface $input, OutputInterface $output): int
    {
        return $this->execute($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = (string) $input->getOption('date');
        $bucket = $input->getOption('bucket') ?: null;
        $prefix = $input->getOption('prefix') ?: null;
        $sse = $input->getOption('sse') ?: null;
        $kms = $input->getOption('kms-key') ?: null;

        $result = $this->shipper->ship($date, $bucket, $prefix, $sse, $kms);
        if (($result['uploaded'] ?? 0) < 1) {
            $output->writeln('<comment>No audit file for date.</comment>');

            return Command::INVALID;
        }

        $output->writeln('<info>Uploaded:</info> '.implode(', ', $result['keys']));
        if (isset($result['etag'])) {
            $output->writeln('<comment>ETag:</comment> '.$result['etag']);
        }

        return Command::SUCCESS;
    }
}
