<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:stats', description: 'Show order statistics')]
final class StatsOrdersCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = $this->em->createQuery('SELECT o.status AS status, COUNT(o.id) AS qty FROM App\Entity\Order o GROUP BY o.status')->getArrayResult();
        $sum = (string) $this->em->createQuery('SELECT COALESCE(SUM(p.amount),0) FROM App\Entity\OrderPayment p')->getSingleScalarResult();
        $count = (int) $this->em->createQuery('SELECT COUNT(o.id) FROM App\Entity\Order o')->getSingleScalarResult();
        $avg = $count > 0 ? number_format(((float) $sum) / $count, 2, '.', '') : '0.00';

        $output->writeln('By status');
        foreach ($rows as $row) {
            if (!is_array($row)) { continue; }
            $status = isset($row['status']) && is_scalar($row['status']) ? (string) $row['status'] : 'unknown';
            $qty = isset($row['qty']) && is_scalar($row['qty']) ? (string) $row['qty'] : '0';
            $output->writeln(sprintf('%s: %s', $status, $qty));
        }
        $output->writeln(sprintf('Total revenue: $%s', $sum));
        $output->writeln(sprintf('Average amount per order: $%s', $avg));

        return Command::SUCCESS;
    }
}
