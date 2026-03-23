<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251017212715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Order Component — Phase 26: Outbox & DLQ Production Policies migration';
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
