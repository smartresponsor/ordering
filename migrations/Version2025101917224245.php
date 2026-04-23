<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 78 — Release Health & Rollbacks';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_release_health (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_release_health
SQL
        );
    }
}
