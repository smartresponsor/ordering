<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144924 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 37 — Disputes & Chargebacks'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_disputes (id SERIAL PRIMARY KEY, order_id INT NOT NULL, amount_cents INT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_disputes
SQL
        );
    }
}
