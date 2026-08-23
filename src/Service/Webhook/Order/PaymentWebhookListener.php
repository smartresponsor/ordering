<?php

declare(strict_types=1);

namespace App\Ordering\Service\Webhook\Order;

use App\Ordering\Service\Messaging\Order\TransactionalEventPublisher;
use App\Ordering\Service\Payment\Order\PaymentService;
use App\Ordering\ServiceInterface\Webhook\Order\PaymentWebhookListenerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class PaymentWebhookListener implements EventSubscriberInterface, PaymentWebhookListenerInterface
{
    public function __construct(private PaymentService $service, private TransactionalEventPublisher $publisher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['payment.webhook' => 'onWebhook'];
    }

    public function onWebhook(object $event): void
    {
        // $event РґРѕР»Р¶РµРЅ РёРјРµС‚СЊ orderId, amount, txId
        if (method_exists($event, 'orderId') && method_exists($event, 'amount') && method_exists($event, 'txId')) {
            $this->service->applyPayment($event->orderId(), $event->amount(), $event->txId());
            $this->publisher->publish('order.paid', ['orderId' => $event->orderId(), 'amount' => $event->amount(), 'txId' => $event->txId()]);
        }
    }
}
