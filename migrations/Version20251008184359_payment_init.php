<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008184359_payment_init extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init payment table with sample row';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS payment (
            id CHAR(36) NOT NULL,
            order_id VARCHAR(64) NOT NULL,
            amount NUMERIC(15,4) NOT NULL,
            currency CHAR(3) NOT NULL,
            status VARCHAR(20) NOT NULL,
            gateway_reference VARCHAR(128) DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_payment_order ON payment (order_id)');
        $this->addSql("INSERT INTO payment (id, order_id, amount, currency, status, gateway_reference, created_at, updated_at)
            VALUES ('uuid-pay-1', 'order-1', 100.00, 'USD', 'paid', 'GW-EXAMPLE', NOW(), NOW())");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS payment');
    }
}
