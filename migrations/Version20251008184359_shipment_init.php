<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008184359_shipment_init extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init shipment table with sample row';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS shipment (
            id CHAR(36) NOT NULL,
            order_id VARCHAR(64) NOT NULL,
            carrier VARCHAR(32) NOT NULL,
            tracking_number VARCHAR(64) DEFAULT NULL,
            status VARCHAR(32) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_shipment_order ON shipment (order_id)');
        $this->addSql("INSERT INTO shipment (id, order_id, carrier, tracking_number, status, created_at, updated_at)
            VALUES ('uuid-ship-1', 'order-1', 'DHL', 'TRACK-001', 'delivered', NOW(), NOW())");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS shipment');
    }
}