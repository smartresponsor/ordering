<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392471 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 118 — Anomaly Detection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_anomalies (id SERIAL PRIMARY KEY, kind VARCHAR(32) NOT NULL, detected_at TIMESTAMP NOT NULL DEFAULT NOW())
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_anomalies
SQL
        );
    }
}
