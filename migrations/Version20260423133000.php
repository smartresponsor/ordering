<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423133000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce Doctrine-first order item, status history, payment allocation, and shipment item tables';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Only PostgreSQL migration path is implemented for this storage alignment.');

        $this->addSql("CREATE TABLE IF NOT EXISTS order_item (id SERIAL NOT NULL, order_id UUID DEFAULT NULL, sku VARCHAR(128) NOT NULL, quantity INT NOT NULL, base_price NUMERIC(12, 2) NOT NULL, currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))");
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_item_order ON order_item (order_id)');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT IF EXISTS fk_order_item_order');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_order_item_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("CREATE TABLE IF NOT EXISTS order_status_history (id SERIAL NOT NULL, order_id UUID NOT NULL, previous_status VARCHAR(32) NOT NULL, new_status VARCHAR(32) NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))");
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_status_history_order ON order_status_history (order_id)');
        $this->addSql('ALTER TABLE order_status_history DROP CONSTRAINT IF EXISTS fk_order_status_history_order');
        $this->addSql('ALTER TABLE order_status_history ADD CONSTRAINT fk_order_status_history_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("CREATE TABLE IF NOT EXISTS order_payment_allocation (id SERIAL NOT NULL, payment_id INT NOT NULL, order_item_id INT DEFAULT NULL, amount NUMERIC(12, 2) NOT NULL, PRIMARY KEY(id))");
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_allocation_payment ON order_payment_allocation (payment_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_allocation_item ON order_payment_allocation (order_item_id)');
        $this->addSql('ALTER TABLE order_payment_allocation DROP CONSTRAINT IF EXISTS fk_order_payment_allocation_payment');
        $this->addSql('ALTER TABLE order_payment_allocation DROP CONSTRAINT IF EXISTS fk_order_payment_allocation_item');
        $this->addSql('ALTER TABLE order_payment_allocation ADD CONSTRAINT fk_order_payment_allocation_payment FOREIGN KEY (payment_id) REFERENCES order_payment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_payment_allocation ADD CONSTRAINT fk_order_payment_allocation_item FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql("CREATE TABLE IF NOT EXISTS order_shipment_item (id SERIAL NOT NULL, shipment_id INT NOT NULL, order_item_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))");
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_shipment_item_shipment ON order_shipment_item (shipment_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_shipment_item_item ON order_shipment_item (order_item_id)');
        $this->addSql('ALTER TABLE order_shipment_item DROP CONSTRAINT IF EXISTS fk_order_shipment_item_shipment');
        $this->addSql('ALTER TABLE order_shipment_item DROP CONSTRAINT IF EXISTS fk_order_shipment_item_item');
        $this->addSql('ALTER TABLE order_shipment_item ADD CONSTRAINT fk_order_shipment_item_shipment FOREIGN KEY (shipment_id) REFERENCES order_shipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_shipment_item ADD CONSTRAINT fk_order_shipment_item_item FOREIGN KEY (order_item_id) REFERENCES order_item (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Only PostgreSQL migration path is implemented for this storage alignment.');

        $this->addSql('DROP TABLE IF EXISTS order_shipment_item');
        $this->addSql('DROP TABLE IF EXISTS order_payment_allocation');
        $this->addSql('DROP TABLE IF EXISTS order_status_history');
        $this->addSql('DROP TABLE IF EXISTS order_item');
    }
}
