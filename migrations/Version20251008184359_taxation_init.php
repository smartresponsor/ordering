<?php
declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251008184359_taxation_init extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init taxation_rule table with sample row';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS taxation_rule (
            id CHAR(36) NOT NULL,
            region_code VARCHAR(8) NOT NULL,
            rate DOUBLE PRECISION NOT NULL,
            description VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_taxation_region ON taxation_rule (region_code)');
        $this->addSql("INSERT INTO taxation_rule (id, region_code, rate, description)
            VALUES ('uuid-tax-1', 'US', 0.07, 'Default US sales tax')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS taxation_rule');
    }
}