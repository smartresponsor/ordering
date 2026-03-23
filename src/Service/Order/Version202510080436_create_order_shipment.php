<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080436_create_order_shipment extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_shipment';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_shipment (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            status VARCHAR(24) NOT NULL,
            carrier VARCHAR(24) NULL,
            tracking_code VARCHAR(64) NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE NULL,
            delivery_proof_url VARCHAR(255) NULL,
            CONSTRAINT FK_SHIPMENT_ORDER FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_shipment_order_status ON order_shipment (order_id, status)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_shipment');
    }
}
