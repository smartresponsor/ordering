<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080244_create_order_metrics_rollup_view extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_metrics_rollup_view (quarter/year aggregates)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS order_metrics_rollup_view (
            id SERIAL PRIMARY KEY,
            vendor_id VARCHAR(36) NOT NULL,
            period_type VARCHAR(8) NOT NULL,
            period_value VARCHAR(8) NOT NULL,
            total_orders INT NOT NULL DEFAULT 0,
            total_revenue NUMERIC(20,2) NOT NULL DEFAULT 0,
            refunded_amount NUMERIC(20,2) NOT NULL DEFAULT 0,
            ltv NUMERIC(20,2) NOT NULL DEFAULT 0,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_vendor_period_roll ON order_metrics_rollup_view (vendor_id, period_type, period_value)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_metrics_rollup_view');
    }
}