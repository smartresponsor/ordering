<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194193 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 62 — Feature Flags';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_feature_flags (name VARCHAR(64) PRIMARY KEY, enabled BOOLEAN NOT NULL DEFAULT false)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_feature_flags
SQL
        );
    }
}
