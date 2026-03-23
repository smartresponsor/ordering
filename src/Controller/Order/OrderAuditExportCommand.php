<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Entity\Order\OrderAuditLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:audit:export', description: 'Export audit logs to CSV')]
final class OrderAuditExportCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('from', InputArgument::REQUIRED, 'From date (Y-m-d)')
             ->addArgument('to', InputArgument::REQUIRED, 'To date (Y-m-d)')
             ->addArgument('file', InputArgument::REQUIRED, 'Output CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $from = new \DateTimeImmutable($input->getArgument('from'));
        $to = new \DateTimeImmutable($input->getArgument('to'));
        $file = $input->getArgument('file');

        $qb = $this->em->createQueryBuilder()
            ->select('a')
            ->from(OrderAuditLog::class, 'a')
            ->where('a.createdAt BETWEEN :f AND :t')
            ->setParameter('f', $from->format('Y-m-d 00:00:00'))
            ->setParameter('t', $to->format('Y-m-d 23:59:59'))
            ->orderBy('a.createdAt', 'ASC');

        $fh = fopen($file, 'w');
        fputcsv($fh, ['order_id', 'event', 'actor', 'created_at', 'payload']);
        foreach ($qb->getQuery()->getResult() as $a) {
            fputcsv($fh, [
                $a->getOrder()->getId(),
                (new \ReflectionClass($a))->getProperty('event')->getValue($a) ?? '',
                (new \ReflectionClass($a))->getProperty('actor')->getValue($a) ?? '',
                (new \ReflectionClass($a))->getProperty('createdAt')->getValue($a)->format('c'),
                json_encode((new \ReflectionClass($a))->getProperty('payload')->getValue($a) ?? []),
            ]);
        }
        fclose($fh);
        $output->writeln('Exported to '.$file);

        return Command::SUCCESS;
    }
}
