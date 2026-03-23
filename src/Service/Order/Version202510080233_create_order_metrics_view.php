<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080233_create_order_metrics_view extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_metrics_view table for analytics CQRS read model';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_metrics_view (
            id SERIAL PRIMARY KEY,
            vendor_id VARCHAR(36) NOT NULL,
            total_orders INT NOT NULL DEFAULT 0,
            completed_orders INT NOT NULL DEFAULT 0,
            cancelled_orders INT NOT NULL DEFAULT 0,
            total_revenue NUMERIC(20,2) NOT NULL DEFAULT 0,
            refunded_amount NUMERIC(20,2) NOT NULL DEFAULT 0,
            avg_order_value NUMERIC(20,2) NOT NULL DEFAULT 0,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS uniq_metrics_vendor ON order_metrics_view (vendor_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_metrics_view');
    }
}
