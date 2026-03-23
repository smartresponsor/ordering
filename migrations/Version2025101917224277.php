<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224277 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 67 — Customer Notifications & Comms'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_notifications (id SERIAL PRIMARY KEY, channel VARCHAR(16) NOT NULL, template VARCHAR(64) NOT NULL, created_at TIMESTAMP NOT NULL DEFAULT NOW())
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_notifications
SQL
        );
    }
}
