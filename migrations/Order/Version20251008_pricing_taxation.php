<?php
declare(strict_types=1);
namespace OrderComponent\Migrations\Order;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008_pricing_taxation extends AbstractMigration
{
    public function getDescription(): string
    { return 'Create order_price_detail table (pricing/taxation snapshot)'; }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_price_detail (
            id UUID NOT NULL,
            currency VARCHAR(3) NOT NULL,
            subtotal_minor BIGINT NOT NULL,
            discount_minor BIGINT NOT NULL,
            tax_minor BIGINT NOT NULL,
            total_minor BIGINT NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    { $this->addSql('DROP TABLE order_price_detail'); }
}