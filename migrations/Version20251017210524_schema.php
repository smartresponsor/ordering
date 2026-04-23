<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251017210524_schema extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create orders table (Phase 12)';
    }

    public function up(Schema $schema): void
    {
        // $schema->createTable('orders') ... (left as placeholder)
    }

    public function down(Schema $schema): void
    {
        // dropTable('orders')
    }
}
