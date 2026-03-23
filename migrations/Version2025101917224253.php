<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224253 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 74 — Accessibility & UX Metrics'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_a11y_scores (id SERIAL PRIMARY KEY, score NUMERIC(5,2) NOT NULL, area VARCHAR(32) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_a11y_scores
SQL
        );
    }
}
