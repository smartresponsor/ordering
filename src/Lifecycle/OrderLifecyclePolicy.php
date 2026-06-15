<?php

declare(strict_types=1);

namespace App\Lifecycle;

/**
 * Canonical lifecycle transition policy for this component.
 *
 * It is intentionally independent from Doctrine so entities, services, forms,
 * and CLI importers can use the same transition guard.
 */
final class OrderLifecyclePolicy
{
    /** @var array<string, list<string>> */
    private const ALLOWED = [
        'draft' => ['placed', 'cancelled'],
        'placed' => ['confirmed', 'paid', 'cancelled'],
        'confirmed' => ['paid', 'cancelled'],
        'paid' => ['fulfillment_pending', 'partially_shipped', 'shipped', 'partially_refunded', 'refunded', 'disputed'],
        'fulfillment_pending' => ['partially_shipped', 'shipped', 'cancelled'],
        'partially_shipped' => ['shipped', 'delivered', 'completed', 'disputed'],
        'shipped' => ['delivered', 'completed', 'returned', 'disputed'],
        'delivered' => ['completed', 'returned', 'disputed'],
        'completed' => ['partially_refunded', 'refunded', 'disputed'],
        'partially_refunded' => ['refunded', 'disputed'],
        'disputed' => ['completed', 'refunded', 'cancelled'],
        'returned' => ['refunded', 'completed'],
        'cancelled' => [],
        'refunded' => [],
    ];

    public static function canTransition(string $from, string $to): bool
    {
        $from = strtolower(trim($from));
        $to = strtolower(trim($to));

        return $from === $to || in_array($to, self::ALLOWED[$from] ?? [], true);
    }

    public static function assertCanTransition(string $from, string $to): void
    {
        if (!self::canTransition($from, $to)) {
            throw new \DomainException(sprintf('Invalid lifecycle transition from "%s" to "%s".', $from, $to));
        }
    }

    /** @return list<string> */
    public static function knownStates(): array
    {
        return array_values(array_unique(array_merge(array_keys(self::ALLOWED), ...array_values(self::ALLOWED))));
    }
}
