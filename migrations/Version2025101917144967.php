<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144967 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 36 — Subscriptions & Recurring Billing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_subscriptions (id SERIAL PRIMARY KEY, external_id VARCHAR(64) NOT NULL UNIQUE)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_subscriptions
SQL
        );
    }
}
