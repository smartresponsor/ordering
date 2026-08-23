<?php

declare(strict_types=1);

namespace App\Ordering\Command\Operations\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'order:list', description: 'List orders and payments')]
final class OrderListCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $orders = $this->em->getRepository(OrderEntity::class)->findAll();
        $payments = $this->em->getRepository(OrderPaymentEntity::class)->findAll();

        $io->writeln(sprintf('Orders: %d', count($orders)));
        foreach ($orders as $order) {
            if (!$order instanceof OrderEntity) {
                continue;
            }
            $io->writeln(sprintf('- Order #%s status=%s', $order->getId(), $order->getStatus()));
        }

        $total = '0.00';
        foreach ($payments as $payment) {
            if (!$payment instanceof OrderPaymentEntity) {
                continue;
            }
            $total = bcadd($total, $payment->getAmount(), 2);
        }
        $io->writeln(sprintf('Payments: %d, Total: $%s', count($payments), $total));

        return Command::SUCCESS;
    }
}
