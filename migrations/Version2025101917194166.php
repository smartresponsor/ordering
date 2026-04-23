<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194166 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 51 — Refunds Advanced';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_refunds_adv (id SERIAL PRIMARY KEY, reason VARCHAR(64) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_refunds_adv
SQL
        );
    }
}
