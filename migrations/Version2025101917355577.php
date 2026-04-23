<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355577 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 87 — PII Encryption-at-Rest & Rotation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_pii_keys (id SERIAL PRIMARY KEY, kid VARCHAR(64) NOT NULL UNIQUE, rotated_at TIMESTAMP NOT NULL DEFAULT NOW())
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_pii_keys
SQL
        );
    }
}
