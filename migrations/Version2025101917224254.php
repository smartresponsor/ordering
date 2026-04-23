<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 75 — Edge Caching & CDN Invalidation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_cdn_invalidation (id SERIAL PRIMARY KEY, path VARCHAR(256) NOT NULL, executed_at TIMESTAMP NOT NULL DEFAULT NOW())
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_cdn_invalidation
SQL
        );
    }
}
