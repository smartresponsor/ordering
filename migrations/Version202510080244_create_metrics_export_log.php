<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080244_create_metrics_export_log extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create metrics_export_log table for idempotent exports';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS metrics_export_log (
            id SERIAL PRIMARY KEY,
            hash VARCHAR(64) NOT NULL UNIQUE,
            sink VARCHAR(32) NOT NULL,
            since_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS metrics_export_log');
    }
}
