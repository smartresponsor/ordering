<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025100904_order_returns_refunds extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables for order returns and refund transactions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE order_return_request (id VARCHAR(64) NOT NULL, order_id VARCHAR(64) NOT NULL, amount_minor INT NOT NULL, currency VARCHAR(8) NOT NULL, reason TEXT DEFAULT NULL, status VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, approved_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refunded_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE order_refund_transaction (id VARCHAR(64) NOT NULL, order_id VARCHAR(64) NOT NULL, return_request_id VARCHAR(64) NOT NULL, amount_minor INT NOT NULL, currency VARCHAR(8) NOT NULL, payment_id VARCHAR(64) NOT NULL, status VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE order_return_request');
        $this->addSql('DROP TABLE order_refund_transaction');
    }
}
