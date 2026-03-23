<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392452 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 114 — PII Tokenization & GDPR Controls'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_pii_tokens (id SERIAL PRIMARY KEY, token VARCHAR(128) NOT NULL UNIQUE, mapped VARCHAR(128) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_pii_tokens
SQL
        );
    }
}
