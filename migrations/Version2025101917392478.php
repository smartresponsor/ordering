<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392478 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 111 — Queue Backlog Autoscaling'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_queue_autoscale (id SERIAL PRIMARY KEY, queue VARCHAR(64) NOT NULL, policy VARCHAR(64) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_queue_autoscale
SQL
        );
    }
}
