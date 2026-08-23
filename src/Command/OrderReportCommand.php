<?php

declare(strict_types=1);

namespace App\Ordering\Command;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Repository\Order\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:order:report', description: 'Print a compact order status report')]
final class OrderReportCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $orders = $repo->findBy([], ['createdAt' => 'DESC']);

        if ([] === $orders) {
            $io->warning('No orders found.');

            return Command::SUCCESS;
        }

        $rows = [];
        foreach ($orders as $order) {
            $rows[] = [
                $order->slug(),
                $order->getStatus(),
                $order->getCurrency(),
                $order->getGrandTotal(),
                $order->getPaidTotal(),
                $order->getRefundedTotal(),
            ];
        }

        $io->table(['ID', 'Status', 'Currency', 'Grand', 'Paid', 'Refunded'], $rows);

        return Command::SUCCESS;
    }
}
