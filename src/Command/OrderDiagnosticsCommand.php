<?php

declare(strict_types=1);

namespace App\Ordering\Command;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:order:diagnostics', description: 'Display basic order component diagnostics')]
final class OrderDiagnosticsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $connection = $this->em->getConnection();
        $platform = $connection->getDatabasePlatform();

        $io->definitionList(
            ['Database platform' => $platform::class],
            ['Orders table exists' => $connection->createSchemaManager()->tablesExist(['orders']) ? 'yes' : 'no'],
            ['Pending migrations' => class_exists(DependencyFactory::class) ? 'check with doctrine:migrations:status' : 'bundle unavailable'],
        );

        return Command::SUCCESS;
    }
}
