<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194222 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 65 — Error Catalog'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_error_catalog (code VARCHAR(16) PRIMARY KEY, message VARCHAR(128) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_error_catalog
SQL
        );
    }
}
