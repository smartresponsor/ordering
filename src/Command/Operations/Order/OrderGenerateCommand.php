<?php

declare(strict_types=1);

namespace App\Ordering\Command\Operations\Order;

use App\Ordering\Factory\OrderFactory;
use App\Ordering\ValueObject\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'order:generate', description: 'Generate N orders via Foundry with optional payments')]
final class OrderGenerateCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('count', InputArgument::OPTIONAL, 'How many orders to generate', 5)
            ->addOption('status', null, InputOption::VALUE_REQUIRED, 'Force status for all orders')
            ->addOption('seed', null, InputOption::VALUE_REQUIRED, 'Seed for RNG')
            ->addOption('with-payment', null, InputOption::VALUE_NONE, 'Create OrderPayment for each order');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $count = (int) $input->getArgument('count');
        if ($count <= 0) {
            $io->error('Count must be > 0');

            return Command::FAILURE;
        }

        $seed = $input->getOption('seed');
        if (null !== $seed && '' !== $seed) {
            mt_srand((int) $seed);
        }

        $forcedStatus = $this->resolveForcedStatus($input->getOption('status'), $io);
        if (false === $forcedStatus) {
            return Command::FAILURE;
        }

        $proxies = OrderFactory::createMany($count);
        $ids = [];
        $paymentTotal = '0.00';
        foreach ($proxies as $proxy) {
            $order = $proxy->object();
            if ($forcedStatus instanceof OrderStatus) {
                $order->setStatus($forcedStatus);
            }
            $this->em->persist($order);
            $this->em->flush();
            $ids[] = $order->getId();

            if ($input->getOption('with-payment')) {
                $order->applyPayment('1000.00', 'PAY-'.$order->getId());
                $paymentTotal = bcadd($paymentTotal, '1000.00', 2);
                $this->em->persist($order);
            }
        }
        $this->em->flush();

        $withPayments = (bool) $input->getOption('with-payment');
        $io->success(sprintf(
            $withPayments ? 'Created %d orders with payments' : 'Created %d orders',
            count($ids),
        ));
        if ($forcedStatus instanceof OrderStatus) {
            $io->writeln(sprintf('Status: %s', $forcedStatus->value));
        }
        if ($withPayments) {
            $io->writeln(sprintf('Payment total: $%s', $paymentTotal));
        }
        $io->writeln('IDs: ['.implode(', ', $ids).']');

        return Command::SUCCESS;
    }

    private function resolveForcedStatus(mixed $statusOption, SymfonyStyle $io): OrderStatus|false|null
    {
        if (null === $statusOption || '' === $statusOption) {
            return null;
        }

        $status = strtolower((string) $statusOption);
        $map = [
            'draft' => OrderStatus::Draft,
            'placed' => OrderStatus::Placed,
            'paid' => OrderStatus::Paid,
            'shipped' => OrderStatus::Shipped,
            'completed' => OrderStatus::Completed,
            'cancelled' => OrderStatus::Cancelled,
            'refunded' => OrderStatus::Refunded,
        ];

        if (!isset($map[$status])) {
            $io->error('Unknown status: '.$status);

            return false;
        }

        return $map[$status];
    }
}
