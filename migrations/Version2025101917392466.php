<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392466 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 102 — Checkout UX Flows'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_checkout_flows (id SERIAL PRIMARY KEY, variant VARCHAR(16) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_checkout_flows
SQL
        );
    }
}
