<?php

declare(strict_types=1);

namespace Tests\Unit\Form;

use App\Ordering\Form\OrderCreateType;
use App\Ordering\Form\OrderPaymentType;
use App\Ordering\Form\OrderRefundType;
use App\Ordering\Form\OrderShipmentType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;

final class OrderFormsTest extends TestCase
{
    #[Test]
    public function orderFormsExposeOperatorRelevantFields(): void
    {
        $factory = Forms::createFormFactory();

        self::assertSame(['currency', 'grandTotal', 'submit'], array_keys($factory->create(OrderCreateType::class)->all()));
        self::assertSame(['amount', 'externalRef', 'submit'], array_keys($factory->create(OrderPaymentType::class)->all()));
        self::assertSame(['amount', 'reason', 'submit'], array_keys($factory->create(OrderRefundType::class)->all()));
        self::assertSame(['carrier', 'note', 'submit'], array_keys($factory->create(OrderShipmentType::class)->all()));
    }
}
