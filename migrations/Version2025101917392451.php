<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 120 — Release Governance & Approvals';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_release_approvals (id SERIAL PRIMARY KEY, gate VARCHAR(32) NOT NULL, approver VARCHAR(64) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_release_approvals
SQL
        );
    }
}
