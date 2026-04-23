<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 93 — Incident Runbooks';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_runbooks (id SERIAL PRIMARY KEY, slug VARCHAR(64) NOT NULL UNIQUE)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_runbooks
SQL
        );
    }
}
