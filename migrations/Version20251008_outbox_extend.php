<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008_outbox_extend extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Extend outbox_message: status/attempts/last_error/routing_key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE outbox_message ADD status VARCHAR(32) NOT NULL DEFAULT 'pending'");
        $this->addSql('ALTER TABLE outbox_message ADD attempts INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE outbox_message ADD last_error TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE outbox_message ADD routing_key VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE outbox_message DROP status');
        $this->addSql('ALTER TABLE outbox_message DROP attempts');
        $this->addSql('ALTER TABLE outbox_message DROP last_error');
        $this->addSql('ALTER TABLE outbox_message DROP routing_key');
    }
}
