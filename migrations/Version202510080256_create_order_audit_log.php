<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080256_create_order_audit_log extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_audit_log table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS order_audit_log (
            id SERIAL PRIMARY KEY,
            occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            actor_type VARCHAR(24) NOT NULL,
            actor_id VARCHAR(64) NULL,
            action VARCHAR(64) NOT NULL,
            subject_type VARCHAR(64) NULL,
            subject_id VARCHAR(64) NULL,
            ip VARCHAR(45) NULL,
            user_agent VARCHAR(255) NULL,
            payload JSON NULL,
            hash VARCHAR(64) NULL
        )");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_audit_actor ON order_audit_log (actor_type, actor_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_audit_subject ON order_audit_log (subject_type, subject_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_audit_time ON order_audit_log (occurred_at)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_audit_log');
    }
}