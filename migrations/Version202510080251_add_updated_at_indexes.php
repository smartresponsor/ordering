<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080251_add_updated_at_indexes extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add updated_at indexes to analytics tables for retention/export performance';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_metrics_view_updated_at ON order_metrics_view (updated_at)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_metrics_agg_updated_at ON order_metrics_aggregate_view (updated_at)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_metrics_roll_updated_at ON order_metrics_rollup_view (updated_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_metrics_view_updated_at');
        $this->addSql('DROP INDEX IF EXISTS idx_metrics_agg_updated_at');
        $this->addSql('DROP INDEX IF EXISTS idx_metrics_roll_updated_at');
    }
}
