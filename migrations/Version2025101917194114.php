<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 52 — RMA & Returns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_rma (id SERIAL PRIMARY KEY, rma_code VARCHAR(32) NOT NULL UNIQUE)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_rma
SQL
        );
    }
}
