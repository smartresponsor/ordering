<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008_order_price_audit extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_price_audit';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_price_audit (
            id UUID NOT NULL,
            order_id UUID NOT NULL,
            occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            currency VARCHAR(3) NOT NULL,
            subtotal_minor BIGINT NOT NULL,
            discount_minor BIGINT NOT NULL,
            tax_minor BIGINT NOT NULL,
            total_minor BIGINT NOT NULL,
            reason VARCHAR(128) NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_price_audit');
    }
}
