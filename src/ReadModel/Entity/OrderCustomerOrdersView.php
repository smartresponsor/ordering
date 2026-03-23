<?php

declare(strict_types=1);

namespace App\ReadModel\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_customer_orders_view')]
class OrderCustomerOrdersView
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $id;

    #[ORM\Column(type: 'string', length: 64)]
    private string $customerId;

    #[ORM\Column(type: 'string', length: 64)]
    private string $orderId;

    #[ORM\Column(type: 'string', length: 32, options: ['default' => 'draft'])]
    private string $status = 'draft';

    public function __construct(string $id, string $customerId, string $orderId)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->orderId = $orderId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
