<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194156 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 61 — A/B Testing'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_ab_tests (id SERIAL PRIMARY KEY, variant VARCHAR(8) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_ab_tests
SQL
        );
    }
}
