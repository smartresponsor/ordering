<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080400_add_refunded_amount_to_order_payment extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add refunded_amount,status to order_payment';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_payment ADD COLUMN IF NOT EXISTS refunded_amount NUMERIC(20,2) NOT NULL DEFAULT 0');
        $this->addSql("ALTER TABLE order_payment ADD COLUMN IF NOT EXISTS status VARCHAR(16) NOT NULL DEFAULT 'captured'");
    }

    public function down(Schema $schema): void
    {
        // no-op
    }
}
