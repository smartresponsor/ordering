<?php

declare(strict_types=1);

namespace App\Ordering\Command\Operations\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderPaymentEntity;
use App\Ordering\Entity\Order\OrderShipmentEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:clear', description: 'Clear orders, payments, and shipments')]
final class OrderClearCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->em->createQuery('DELETE FROM '.OrderShipmentEntity::class.' s')->execute();
        $this->em->createQuery('DELETE FROM '.OrderPaymentEntity::class.' p')->execute();
        $this->em->createQuery('DELETE FROM '.OrderEntity::class.' o')->execute();
        $output->writeln('Orders cleared');

        return Command::SUCCESS;
    }
}
