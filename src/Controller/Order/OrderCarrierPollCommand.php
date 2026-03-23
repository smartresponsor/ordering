<?php

declare(strict_types=1);

namespace App\Controller\Order;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko / Marketing America Corp <dev@smartresponsor.com>
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

use App\Service\Order\CarrierPollingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:carrier:poll', description: 'Poll external carrier for tracking update')]
final class OrderCarrierPollCommand extends Command
{
    public function __construct(private CarrierPollingService $svc)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('carrier', InputArgument::REQUIRED, 'Carrier name (UPS|DHL|FedEx)');
        $this->addArgument('orderId', InputArgument::REQUIRED, 'Order ID');
        $this->addArgument('tracking', InputArgument::REQUIRED, 'Tracking number');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $carrier = (string) $input->getArgument('carrier');
        $orderId = (string) $input->getArgument('orderId');
        $tracking = (string) $input->getArgument('tracking');

        $ok = $this->svc->poll($carrier, $orderId, $tracking);
        $output->writeln($ok ? '<info>Updated</info>' : '<comment>No update</comment>');

        return Command::SUCCESS;
    }
}
