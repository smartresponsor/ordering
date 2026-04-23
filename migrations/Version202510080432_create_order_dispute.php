<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080432_create_order_dispute extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_dispute table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_dispute (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            external_id VARCHAR(64) NULL,
            type VARCHAR(24) NOT NULL,
            reason VARCHAR(64) NULL,
            status VARCHAR(32) NOT NULL,
            opened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            resolved_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
            CONSTRAINT FK_DISPUTE_ORDER FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_dispute_order_status ON order_dispute (order_id, status)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_dispute');
    }
}
