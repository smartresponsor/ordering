<?php

declare(strict_types=1);

namespace App\ValueObject;

enum OrderStatus: string
{
    case Draft = 'draft';
    case Placed = 'placed';
    case Confirmed = 'confirmed';
    case Paid = 'paid';
    case FulfillmentPending = 'fulfillment_pending';
    case PartiallyShipped = 'partially_shipped';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case PartiallyRefunded = 'partially_refunded';
    case Refunded = 'refunded';
    case Disputed = 'disputed';
    case Returned = 'returned';

    /** @return list<string> */
    public static function values(): array
    {
        return array_map(static fn (self $status): string => $status->value, self::cases());
    }
}
