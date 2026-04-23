<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 113 — BI Semantic Layer';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_bi_semantic (id SERIAL PRIMARY KEY, entity VARCHAR(64) NOT NULL, schema_json JSON NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_bi_semantic
SQL
        );
    }
}
