<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080522_create_order_audit_log extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_audit_log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_audit_log (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            event VARCHAR(64) NOT NULL,
            actor VARCHAR(120) NULL,
            payload JSON NULL,
            ip_address VARCHAR(48) NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            CONSTRAINT FK_AUDIT_ORDER FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_audit_order_created ON order_audit_log (order_id, created_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_audit_log');
    }
}
