<?php
declare(strict_types=1);
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version202510080436_create_order_return_policy extends AbstractMigration
{
    public function getDescription(): string { return 'Create order_return_policy'; }
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS order_return_policy (
            id SERIAL PRIMARY KEY,
            shipment_id INT NOT NULL,
            days_allowed INT NOT NULL DEFAULT 14,
            auto_expire_date TIMESTAMP(0) WITHOUT TIME ZONE NULL,
            CONSTRAINT FK_POLICY_SHIPMENT FOREIGN KEY (shipment_id) REFERENCES order_shipment (id) ON DELETE CASCADE
        )");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_policy_shipment ON order_return_policy (shipment_id)");
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_return_policy');
    }
}
