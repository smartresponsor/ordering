<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@highhopesamerica.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\CommandInterface\Console\Order;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface OrderAuditRotateCommandInterface
{
    public function runCommand(InputInterface $input, OutputInterface $output): int;
}
