<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251017205815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Order Component — Phase 7: Outbox, Webhooks, Idempotency & ReadModels migration';
    }

    public function up(Schema $schema): void
    {
        // No-op for archive scaffold
    }

    public function down(Schema $schema): void
    {
        // No-op
    }
}
