<?php

declare(strict_types=1);

namespace App\Command\Operations\Order;

use App\ReadModel\Entity\OrderView;
use App\ReadModel\Service\OrderReadModelUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'order:readmodel:sync', description: 'Recalculate OrderView for all or a single order')]
final class SyncReadModelCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly OrderReadModelUpdater $updater)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('orderId', InputArgument::OPTIONAL, 'Order ID to recalc (if omitted, all)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderIdArg = $input->getArgument('orderId');
        $orderId = is_scalar($orderIdArg) ? (string) $orderIdArg : '';
        if ($orderId) {
            $view = $this->em->getRepository(OrderView::class)->find($orderId);
            if ($view) {
                $this->updater->recalc($view->getId(), $view->getGrandTotal());
                $output->writeln("<info>Recalculated {$orderId}</info>");
            }

            return Command::SUCCESS;
        }
        $list = $this->em->getRepository(OrderView::class)->findAll();
        foreach ($list as $view) {
            /* @var OrderView $view */
            $this->updater->recalc($view->getId(), $view->getGrandTotal());
        }
        $output->writeln('<info>Recalculated all</info>');

        return Command::SUCCESS;
    }
}
