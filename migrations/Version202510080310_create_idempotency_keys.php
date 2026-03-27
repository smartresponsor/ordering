<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version202510080310_create_idempotency_keys extends AbstractMigration
{
    public function getDescription(): string { return 'Create idempotency_keys table'; }
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS idempotency_keys (
            id SERIAL PRIMARY KEY,
            scope VARCHAR(64) NOT NULL,
            key_hash VARCHAR(64) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_idem_key ON idempotency_keys (scope, key_hash)");
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS idempotency_keys');
    }
}