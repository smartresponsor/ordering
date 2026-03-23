<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025100901_order_payment_projection extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table order_payment_projection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_payment_projection (order_id VARCHAR(64) NOT NULL, payment_id VARCHAR(64) NOT NULL, amount_minor INT NOT NULL, currency VARCHAR(8) NOT NULL, status VARCHAR(32) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(order_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_payment_projection');
    }
}
