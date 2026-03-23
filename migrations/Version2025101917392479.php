<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392479 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 105 — 3DS Exemptions Policy'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_3ds_exemptions (id SERIAL PRIMARY KEY, segment VARCHAR(32) NOT NULL, enabled BOOLEAN NOT NULL DEFAULT false)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_3ds_exemptions
SQL
        );
    }
}
