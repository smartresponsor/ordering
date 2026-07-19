<?php

declare(strict_types=1);

namespace App\Service;

use App\Ordering\Entity\Order\OrderEntity;
use App\Value\Surface\OrderManagementSurfaceContract;
use Symfony\Component\Form\FormView;

final class OrderManagementSurfaceContractFactory
{
    /**
     * @param list<OrderEntity>       $orders
     * @param array<string, FormView> $paymentForms
     * @param array<string, FormView> $shipmentForms
     * @param array<string, FormView> $refundForms
     */
    public function createIndexSurface(array $orders, FormView $createForm, array $paymentForms, array $shipmentForms, array $refundForms): OrderManagementSurfaceContract
    {
        $orderIds = array_map(static fn (OrderEntity $order): string => $order->slug(), $orders);

        return new OrderManagementSurfaceContract(
            OrderManagementSurfaceContract::WORD,
            OrderManagementSurfaceContract::VIEW_MANAGEMENT,
            'order/base.html.twig',
            $this->slotMap(),
            $orders,
            $createForm,
            $paymentForms,
            $shipmentForms,
            $refundForms,
            [
                'left.panel' => [
                    'title' => 'Create order',
                    'createForm' => $createForm,
                ],
                'main.body' => [
                    'title' => 'Orders',
                    'orders' => $orders,
                    'paymentForms' => $paymentForms,
                    'shipmentForms' => $shipmentForms,
                    'refundForms' => $refundForms,
                ],
                'right.panel' => [
                    'title' => 'Summary',
                    'orderCount' => count($orders),
                    'orderIds' => $orderIds,
                    'statuses' => $this->statusCounts($orders),
                ],
            ],
        );
    }

    /** @return array<string, string> */
    private function slotMap(): array
    {
        return [
            'left.panel' => 'Create order',
            'main.body' => 'Orders',
            'right.panel' => 'Summary',
        ];
    }

    /**
     * @param list<OrderEntity> $orders
     *
     * @return array<string, int>
     */
    private function statusCounts(array $orders): array
    {
        $counts = [];

        foreach ($orders as $order) {
            $status = method_exists($order, 'status') ? (string) $order->status() : 'unknown';
            $counts[$status] = ($counts[$status] ?? 0) + 1;
        }

        ksort($counts);

        return $counts;
    }
}
