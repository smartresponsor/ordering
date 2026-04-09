<?php

declare(strict_types=1);

namespace OrderComponent\Migrations\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008_idempotency_outbox extends AbstractMigration
{
    public function getDescription(): string
    { return 'Create idempotency_key and outbox_message'; }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE idempotency_key (key VARCHAR(128) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(key))');
        $this->addSql('CREATE TABLE outbox_message (id UUID NOT NULL, type VARCHAR(128) NOT NULL, payload JSON NOT NULL, occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE idempotency_key');
        $this->addSql('DROP TABLE outbox_message');
    }
}