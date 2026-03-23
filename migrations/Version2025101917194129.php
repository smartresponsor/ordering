<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194129 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 50 — Payments 3DS & SCA'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_payments_sca (id SERIAL PRIMARY KEY, challenge BOOLEAN NOT NULL DEFAULT false)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_payments_sca
SQL
        );
    }
}
