<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392498 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 116 — Audit Trails Cross-Service'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_audit_xsvc (id SERIAL PRIMARY KEY, service VARCHAR(32) NOT NULL, link VARCHAR(256) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_audit_xsvc
SQL
        );
    }
}
