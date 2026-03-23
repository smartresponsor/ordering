<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392423 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 112 — Data Lake Contracts v2'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_datalake_v2 (id SERIAL PRIMARY KEY, contract VARCHAR(64) NOT NULL, version INT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_datalake_v2
SQL
        );
    }
}
