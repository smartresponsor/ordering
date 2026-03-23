<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194180 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 58 — Taxation Rules Engine'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_tax_rules (id SERIAL PRIMARY KEY, rule VARCHAR(64) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_tax_rules
SQL
        );
    }
}
