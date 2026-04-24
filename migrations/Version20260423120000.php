<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align outbox and idempotency storage with Doctrine-first technical entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS message_id VARCHAR(36) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS aggregate_id VARCHAR(64) DEFAULT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS topic VARCHAR(128) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS attempts INT DEFAULT 0 NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS sent BOOLEAN DEFAULT FALSE NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS available_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS sent_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL");
        $this->addSql("UPDATE outbox_messages SET message_id = COALESCE(NULLIF(message_id, ''), md5(random()::text || clock_timestamp()::text)) WHERE COALESCE(message_id, '') = ''");
        $this->addSql("UPDATE outbox_messages SET topic = COALESCE(NULLIF(topic, ''), event_name, event_type, 'order.event') WHERE COALESCE(topic, '') = ''");
        $this->addSql("UPDATE outbox_messages SET sent = COALESCE(dispatched, FALSE) WHERE sent = FALSE");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_outbox_messages_message_id ON outbox_messages (message_id)");

        $this->addSql("ALTER TABLE idempotency_key ADD COLUMN IF NOT EXISTS scope VARCHAR(128) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE idempotency_key ADD COLUMN IF NOT EXISTS raw_key VARCHAR(255) DEFAULT NULL");
        $this->addSql("UPDATE idempotency_key SET scope = COALESCE(NULLIF(scope, ''), key)");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_idempotency_scope_key_hash ON idempotency_key (scope, key_hash)");
    }

    public function down(Schema $schema): void
    {
    }
}
