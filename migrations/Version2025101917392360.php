<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392360 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 97 — Payment Provider Stripe-like Adapter'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_pay_provider_stripe (id SERIAL PRIMARY KEY, merchant VARCHAR(64) NOT NULL, active BOOLEAN NOT NULL DEFAULT true)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_pay_provider_stripe
SQL
        );
    }
}
