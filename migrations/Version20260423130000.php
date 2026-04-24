<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align outbox and idempotency tables for Doctrine-first technical storage';
    }

    public function up(Schema $schema): void
    {
        if ('postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS attempts INT NOT NULL DEFAULT 0');
            $this->addSql('ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS available_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
            $this->addSql('ALTER TABLE idempotency_key ADD COLUMN IF NOT EXISTS scope VARCHAR(128) DEFAULT NULL');
            $this->addSql('ALTER TABLE idempotency_key ADD COLUMN IF NOT EXISTS key_hash VARCHAR(64) DEFAULT NULL');
            $this->addSql("UPDATE idempotency_key SET key_hash = md5(\"key\") WHERE key_hash IS NULL");
            $this->addSql('ALTER TABLE idempotency_key ALTER COLUMN key_hash SET NOT NULL');
            $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS uniq_idempotency_key_hash ON idempotency_key (key_hash)');

            return;
        }

        $this->abortIf(true, 'Only PostgreSQL migration path is implemented for this storage alignment.');
    }

    public function down(Schema $schema): void
    {
        if ('postgresql' === $this->connection->getDatabasePlatform()->getName()) {
            $this->addSql('DROP INDEX IF EXISTS uniq_idempotency_key_hash');
            $this->addSql('ALTER TABLE idempotency_key DROP COLUMN IF EXISTS key_hash');
            $this->addSql('ALTER TABLE idempotency_key DROP COLUMN IF EXISTS scope');
            $this->addSql('ALTER TABLE outbox_messages DROP COLUMN IF EXISTS available_at');
            $this->addSql('ALTER TABLE outbox_messages DROP COLUMN IF EXISTS attempts');

            return;
        }

        $this->abortIf(true, 'Only PostgreSQL migration path is implemented for this storage alignment.');
    }
}
