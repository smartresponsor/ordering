<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392459 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 103 — Saved Payment Methods Vault'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_payment_vault (id SERIAL PRIMARY KEY, token VARCHAR(128) NOT NULL UNIQUE, customer VARCHAR(64) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_payment_vault
SQL
        );
    }
}
