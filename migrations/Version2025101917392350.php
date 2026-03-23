<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392350 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 98 — Payment Provider Adyen-like Adapter'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_pay_provider_adyen (id SERIAL PRIMARY KEY, merchant VARCHAR(64) NOT NULL, active BOOLEAN NOT NULL DEFAULT true)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_pay_provider_adyen
SQL
        );
    }
}
