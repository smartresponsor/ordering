<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080445_create_order_taxation_audit extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_taxation_audit table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS order_taxation_audit (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            subtotal NUMERIC(20,2) NOT NULL,
            tax_total NUMERIC(20,2) NOT NULL,
            discount_total NUMERIC(20,2) NOT NULL,
            final_total NUMERIC(20,2) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            CONSTRAINT FK_TAX_AUDIT_ORDER FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
        )");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_tax_audit_order_created ON order_taxation_audit (order_id, created_at)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_taxation_audit');
    }
}