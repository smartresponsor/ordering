<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423123000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Canonicalize outbox and idempotency technical storage for Doctrine-first ordering';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('outbox_messages')) {
            $table = $schema->getTable('outbox_messages');

            if (!$table->hasColumn('message_id')) {
                $this->addSql('ALTER TABLE outbox_messages ADD message_id VARCHAR(64) DEFAULT NULL');
            }
            if (!$table->hasColumn('topic')) {
                $this->addSql('ALTER TABLE outbox_messages ADD topic VARCHAR(128) DEFAULT NULL');
            }
            if (!$table->hasColumn('attempts')) {
                $this->addSql('ALTER TABLE outbox_messages ADD attempts INT DEFAULT 0 NOT NULL');
            }
            if (!$table->hasColumn('sent')) {
                $this->addSql('ALTER TABLE outbox_messages ADD sent BOOLEAN DEFAULT FALSE NOT NULL');
            }
            if (!$table->hasColumn('available_at')) {
                $this->addSql('ALTER TABLE outbox_messages ADD available_at DATETIME DEFAULT NULL');
            }
        }

        if ($schema->hasTable('idempotency_key')) {
            $table = $schema->getTable('idempotency_key');
            if (!$table->hasColumn('scope')) {
                $this->addSql('ALTER TABLE idempotency_key ADD scope VARCHAR(191) DEFAULT NULL');
            }
            if (!$table->hasColumn('key_hash')) {
                $this->addSql('ALTER TABLE idempotency_key ADD key_hash VARCHAR(64) DEFAULT NULL');
            }
        }
    }

    public function down(Schema $schema): void
    {
    }
}
