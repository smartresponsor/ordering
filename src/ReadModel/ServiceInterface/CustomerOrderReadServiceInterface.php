<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\ServiceInterface;

use App\Ordering\ReadModel\View\CustomerOrderDetail;
use App\Ordering\ReadModel\View\CustomerOrderSummary;

interface CustomerOrderReadServiceInterface
{
    /** @return list<CustomerOrderSummary> */
    public function listForCustomer(string $customerId): array;

    public function findForCustomer(string $customerId, string $orderReference): ?CustomerOrderSummary;

    /** @return list<CustomerOrderDetail> */
    public function listDetailsForCustomer(string $customerId): array;

    public function findDetailForCustomer(string $customerId, string $orderReference): ?CustomerOrderDetail;
}
