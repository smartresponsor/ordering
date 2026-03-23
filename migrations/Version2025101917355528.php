<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355528 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 91 — Blue-Green Deployments'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_bluegreen (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_bluegreen
SQL
        );
    }
}
