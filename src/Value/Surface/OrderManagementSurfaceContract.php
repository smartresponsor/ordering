<?php

declare(strict_types=1);

namespace App\Ordering\Value\Surface;

use App\Interfacing\Contract\InterfaceSurfaceRenderableInterface;

final readonly class OrderManagementSurfaceContract implements InterfaceSurfaceRenderableInterface
{
    public const WORD = 'order';
    public const VIEW_MANAGEMENT = 'management';

    /**
     * @param list<object>          $orders
     * @param array<string, object> $paymentForms
     * @param array<string, object> $shipmentForms
     * @param array<string, object> $refundForms
     * @param array<string, mixed>  $slots
     * @param array<string, string> $slotMap
     */
    public function __construct(
        public string $word,
        public string $view,
        public string $templateName,
        public array $slotMap,
        public array $orders,
        public object $createForm,
        public array $paymentForms,
        public array $shipmentForms,
        public array $refundForms,
        public array $slots,
    ) {
    }

    /** @return array{word: string, view: string, templateName: string, slotMap: array<string, string>, orders: list<object>, createForm: object, paymentForms: array<string, object>, shipmentForms: array<string, object>, refundForms: array<string, object>, slots: array<string, mixed>} */
    public function toTemplateContext(): array
    {
        return [
            'word' => $this->word,
            'view' => $this->view,
            'templateName' => $this->templateName,
            'slotMap' => $this->slotMap,
            'orders' => $this->orders,
            'createForm' => $this->createForm,
            'paymentForms' => $this->paymentForms,
            'shipmentForms' => $this->shipmentForms,
            'refundForms' => $this->refundForms,
            'slots' => $this->slots,
        ];
    }

    /** @return array{word: string, view: string, orderCount: int, orderIds: list<string>, slots: array<string, mixed>} */
    public function toFallbackData(): array
    {
        return [
            'word' => $this->word,
            'view' => $this->view,
            'orderCount' => count($this->orders),
            'orderIds' => array_values(array_filter(array_map(static fn (object $order): ?string => method_exists($order, 'slug') ? (string) $order->slug() : null, $this->orders))),
            'slots' => $this->slots,
        ];
    }

    public function templateName(): string
    {
        return $this->templateName;
    }
}
