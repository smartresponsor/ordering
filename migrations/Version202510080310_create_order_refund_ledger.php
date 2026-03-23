<?php
declare(strict_types=1);
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version202510080310_create_order_refund_ledger extends AbstractMigration
{
    public function getDescription(): string { return 'Create order_refund_ledger table'; }
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS order_refund_ledger (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            idempotency_key VARCHAR(64) NOT NULL,
            amount NUMERIC(20,2) NOT NULL,
            currency VARCHAR(3) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            CONSTRAINT FK_LEDGER_ORDER FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
        )");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_order_refund_key ON order_refund_ledger (order_id, idempotency_key)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_ledger_order ON order_refund_ledger (order_id)");
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_refund_ledger');
    }
}
