<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080509_create_refund_stats_view extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create refund_stats_view';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS refund_stats_view (
            id SERIAL PRIMARY KEY,
            date DATE NOT NULL,
            refund_count INT NOT NULL DEFAULT 0,
            refund_total NUMERIC(20,2) NOT NULL DEFAULT 0
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_refund_stats_date ON refund_stats_view (date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS refund_stats_view');
    }
}
