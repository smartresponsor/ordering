<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 71 — Mobile SDK Contracts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_mobile_sdk_meta (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_mobile_sdk_meta
SQL
        );
    }
}
