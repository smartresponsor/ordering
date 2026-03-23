<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080509_create_order_metrics_projection extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_metrics_projection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_metrics_projection (
            id SERIAL PRIMARY KEY,
            date DATE NOT NULL,
            orders_count INT NOT NULL DEFAULT 0,
            gross_total NUMERIC(20,2) NOT NULL DEFAULT 0,
            refund_total NUMERIC(20,2) NOT NULL DEFAULT 0,
            net_total NUMERIC(20,2) NOT NULL DEFAULT 0
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_omp_date ON order_metrics_projection (date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_metrics_projection');
    }
}
