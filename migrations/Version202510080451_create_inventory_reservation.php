<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version202510080451_create_inventory_reservation extends AbstractMigration
{
    public function getDescription(): string { return 'Create inventory_reservation'; }
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS inventory_reservation (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            reservation_key VARCHAR(80) NOT NULL,
            lines JSON NOT NULL,
            state VARCHAR(16) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            CONSTRAINT FK_INVRES_ORDER FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
        )");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_reservation_key ON inventory_reservation (reservation_key)");
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS inventory_reservation');
    }
}