<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080228_add_refunded_statuses extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Safely add refunded statuses to orders table';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('orders')) {
            $this->write('Table "orders" not found — skipping.');

            return;
        }

        $table = $schema->getTable('orders');
        if ($table->hasColumn('status')) {
            $this->addSql('ALTER TABLE orders DROP CONSTRAINT IF EXISTS chk_status');
            $this->addSql("ALTER TABLE orders ADD CONSTRAINT chk_status CHECK (status IN
                ('draft','placed','paid','shipped','completed','cancelled','refunded_partial','refunded'))");
        } else {
            $this->write('Column "status" not found — skipping status constraint update.');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT IF EXISTS chk_status');
        $this->addSql("ALTER TABLE orders ADD CONSTRAINT chk_status CHECK (status IN ('draft','placed','paid','shipped','completed','cancelled'))");
    }
}
