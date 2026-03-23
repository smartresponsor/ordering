<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:clear', description: 'Clear orders, payments, and shipments')]
final class ClearOrdersCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->em->createQuery('DELETE FROM App\Entity\Order\OrderShipment s')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Order\OrderPayment p')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Order o')->execute();
        $output->writeln('Orders cleared');

        return Command::SUCCESS;
    }
}
