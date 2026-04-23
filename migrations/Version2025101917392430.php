<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 109 — Hot Cold Cache Tiers';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_cache_tiers (tier VARCHAR(16) PRIMARY KEY, ttl_sec INT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_cache_tiers
SQL
        );
    }
}
