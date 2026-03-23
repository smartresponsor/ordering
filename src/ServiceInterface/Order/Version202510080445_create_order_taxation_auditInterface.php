<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use Doctrine\DBAL\Schema\Schema;

interface Version202510080445_create_order_taxation_auditInterface
{
    public function getDescription(): string;

    public function up(Schema $schema): void;

    public function down(Schema $schema): void;
}
