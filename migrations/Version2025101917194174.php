<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194174 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 53 — Fraud Scoring';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_fraud_score (order_id INT PRIMARY KEY, score NUMERIC(5,2) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_fraud_score
SQL
        );
    }
}
