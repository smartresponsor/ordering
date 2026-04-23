<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224265 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 73 — Internationalization & Localization';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_i18n_locales (code VARCHAR(8) PRIMARY KEY, name VARCHAR(64) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_i18n_locales
SQL
        );
    }
}
