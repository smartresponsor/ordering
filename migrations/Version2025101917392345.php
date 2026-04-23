<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 99 — Multi-Acquirer Smart Routing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_smart_routing (id SERIAL PRIMARY KEY, rule VARCHAR(64) NOT NULL, weight INT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_smart_routing
SQL
        );
    }
}
