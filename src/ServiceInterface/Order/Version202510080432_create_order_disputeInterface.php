<?php

declare(strict_types=1);

namespace App\ServiceInterface\Order;

use Doctrine\DBAL\Schema\Schema;

interface Version202510080432_create_order_disputeInterface
{
    public function getDescription(): string;

    public function up(Schema $schema): void;

    public function down(Schema $schema): void;
}
