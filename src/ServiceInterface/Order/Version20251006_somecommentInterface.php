<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use Doctrine\DBAL\Schema\Schema;

interface Version20251006_somecommentInterface
{
    public function getDescription(): string;

    public function up(Schema $schema): void;

    public function down(Schema $schema): void;
}
