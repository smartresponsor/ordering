<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080445_alter_order_item_add_pricing extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add pricing fields to order_item';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_item ADD COLUMN IF NOT EXISTS base_price NUMERIC(20,2) NOT NULL DEFAULT 0');
        $this->addSql("ALTER TABLE order_item ADD COLUMN IF NOT EXISTS currency VARCHAR(3) NOT NULL DEFAULT 'USD'");
        $this->addSql('ALTER TABLE order_item ADD COLUMN IF NOT EXISTS final_price NUMERIC(20,2) NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE order_item ADD COLUMN IF NOT EXISTS tax_rate DOUBLE PRECISION NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE order_item ADD COLUMN IF NOT EXISTS discount_percent DOUBLE PRECISION NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // no-op for simplicity
    }
}
