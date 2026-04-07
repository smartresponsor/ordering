<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ServiceInterface\Security\Order\DlqRepositoryInterface;
use App\ValueObject\Archival\Order\AuditLog;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 * This file is part of SmartResponsor (Order domain).
 */

final class DlqConsole
{
    private DlqRepositoryInterface $repo;
    private AuditLog $audit;

    public function __construct(DlqRepositoryInterface $repo, AuditLog $audit)
    {
        $this->repo = $repo;
        $this->audit = $audit;
    }

    public function seed(): void
    {
        $this->repo->save(['dlq_id' => 'dlq-1', 'provider' => 'stripe', 'reason' => 'timeout', 'payload' => ['id' => 1], 'created_at' => date('c')]);
        $this->repo->save(['dlq_id' => 'dlq-2', 'provider' => 'stripe', 'reason' => 'invalid', 'payload' => ['id' => 2], 'created_at' => date('c')]);
        $this->repo->save(['dlq_id' => 'dlq-3', 'provider' => 'alt', 'reason' => 'network', 'payload' => ['id' => 3], 'created_at' => date('c')]);
        $this->audit->write('seed', ['count' => 3]);
    }

    /** @param array{provider?: string, reason?: string} $filter */
    public function showList(array $filter = []): void
    {
        $list = $this->repo->list($filter);
        echo json_encode($list, JSON_PRETTY_PRINT)."
";
    }

    public function requeue(string $id): void
    {
        $item = $this->repo->get($id);
        if (!$item) {
            echo "Not found
";

            return;
        }
        $this->repo->delete($id);
        $this->audit->write('requeue', ['id' => $id]);
        echo "REQUEUED: $id
";
    }

    public function discard(string $id, string $reason): void
    {
        $item = $this->repo->get($id);
        if (!$item) {
            echo "Not found
";

            return;
        }
        $this->repo->delete($id);
        $this->audit->write('discard', ['id' => $id, 'reason' => $reason]);
        echo "DISCARDED: $id reason=$reason
";
    }
}
