<?php
declare(strict_types=1);
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version202510080509_create_vendor_revenue_view extends AbstractMigration
{
    public function getDescription(): string { return 'Create vendor_revenue_view'; }
    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS vendor_revenue_view (
            id SERIAL PRIMARY KEY,
            vendor_id VARCHAR(64) NOT NULL,
            date DATE NOT NULL,
            orders_count INT NOT NULL DEFAULT 0,
            gross_total NUMERIC(20,2) NOT NULL DEFAULT 0
        )");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_vendor_rev_vendor_date ON vendor_revenue_view (vendor_id, date)");
    }
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS vendor_revenue_view');
    }
}
