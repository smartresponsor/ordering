<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251017205450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 2 scaffold migration';
    }

    public function up(Schema $schema): void
    {
        // No-op for Phase 2 scaffold
    }

    public function down(Schema $schema): void
    {
        // No-op
    }
}
