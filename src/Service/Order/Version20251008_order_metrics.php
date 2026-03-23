<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008_order_metrics extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_metrics projection table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_metrics (
            id UUID NOT NULL,
            vendor_id VARCHAR(64) NOT NULL,
            day DATE NOT NULL,
            orders_count BIGINT NOT NULL,
            paid_minor BIGINT NOT NULL,
            refunded_minor BIGINT NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_order_metrics_vendor_day ON order_metrics (vendor_id, day)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_metrics');
    }
}
