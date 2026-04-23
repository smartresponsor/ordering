<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 86 — BI Contracts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_bi_contracts (id SERIAL PRIMARY KEY, name VARCHAR(64) NOT NULL, schema_version INT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_bi_contracts
SQL
        );
    }
}
