<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20251008_outbox extends AbstractMigration
{
    public function getDescription(): string { return 'Create outbox_message table'; }
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS outbox_message (
          id SERIAL PRIMARY KEY,
          topic VARCHAR(180) NOT NULL,
          payload JSON NOT NULL,
          idempotency_key VARCHAR(64) NOT NULL UNIQUE,
          occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_outbox_occurred ON outbox_message (occurred_at)");
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS outbox_message');
    }
}