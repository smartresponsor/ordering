<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080259_add_audit_indexes_b13 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add additional indexes to order_audit_log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_audit_action_time ON order_audit_log (action, occurred_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_audit_action_time');
    }
}
