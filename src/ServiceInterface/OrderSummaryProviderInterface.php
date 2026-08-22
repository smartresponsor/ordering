<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface;

interface OrderSummaryProviderInterface
{
    /**
     * @return array{
     *   item: list<array{
     *     id: string,
     *     status: string,
     *     createdAtIso: string,
     *     totalGross: float,
     *     currencyCode: string,
     *     customerEmail: ?string
     *   }>,
     *   total: int,
     *   page: int,
     *   pageSize: int
     * }
     */
    public function fetchPage(
        string $tenantId,
        int $page,
        int $pageSize,
        ?string $status,
        ?string $createdFromIso,
        ?string $createdToIso,
    ): array;
}
