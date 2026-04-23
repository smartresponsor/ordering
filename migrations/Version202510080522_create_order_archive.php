<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080522_create_order_archive extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_archive';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_archive (
            id SERIAL PRIMARY KEY,
            order_number VARCHAR(64) NOT NULL,
            snapshot JSON NOT NULL,
            archived_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_archive_number_at ON order_archive (order_number, archived_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_archive');
    }
}
