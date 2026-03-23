<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008_partial_payment_refund extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order_partial_payment and order_refund tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_partial_payment (
            id UUID NOT NULL,
            order_id UUID NOT NULL,
            amount_minor BIGINT NOT NULL,
            currency VARCHAR(3) NOT NULL,
            paid_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            payment_method VARCHAR(64) NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE order_refund (
            id UUID NOT NULL,
            order_id UUID NOT NULL,
            amount_minor BIGINT NOT NULL,
            currency VARCHAR(3) NOT NULL,
            refunded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            reason VARCHAR(64) NOT NULL,
            payment_ref VARCHAR(64) DEFAULT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_partial_payment');
        $this->addSql('DROP TABLE order_refund');
    }
}
