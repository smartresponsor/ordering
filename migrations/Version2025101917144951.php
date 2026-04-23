<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 48 — Final LTS Release Pipeline';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_release_lts (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_release_lts
SQL
        );
    }
}
