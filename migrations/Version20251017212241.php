<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251017212241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Order Component — Phase 21: Inventory & Webhooks Enhancement migration';
    }
    public function up(Schema $schema): void
    {
        // No-op for scaffold
    }
    public function down(Schema $schema): void
    {
        // No-op
    }
}
