<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355566 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 90 — Legal & Audit Retention v2';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_audit_retention_v2 (id SERIAL PRIMARY KEY, ttl_days INT NOT NULL DEFAULT 730)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_audit_retention_v2
SQL
        );
    }
}
