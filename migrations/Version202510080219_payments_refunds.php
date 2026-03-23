<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080219_payments_refunds extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tables for partial payments, refunds and webhook idempotency';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS order_payment_part (
            id SERIAL PRIMARY KEY,
            order_id VARCHAR(36) NOT NULL,
            payment_id VARCHAR(64) NOT NULL,
            currency VARCHAR(16) NOT NULL,
            amount NUMERIC(20,2) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_opp_order ON order_payment_part (order_id)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_refund (
            id SERIAL PRIMARY KEY,
            order_id VARCHAR(36) NOT NULL,
            refund_id VARCHAR(64) NOT NULL,
            currency VARCHAR(16) NOT NULL,
            amount NUMERIC(20,2) NOT NULL,
            reason VARCHAR(255) NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_or_order ON order_refund (order_id)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_webhook_log (
            id SERIAL PRIMARY KEY,
            idempotency_key VARCHAR(120) NOT NULL UNIQUE,
            event_type VARCHAR(32) NOT NULL,
            payload TEXT NOT NULL,
            received_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_payment_part');
        $this->addSql('DROP TABLE IF EXISTS order_refund');
        $this->addSql('DROP TABLE IF EXISTS order_webhook_log');
    }
}
