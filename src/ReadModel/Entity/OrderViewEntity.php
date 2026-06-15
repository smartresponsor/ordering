<?php

declare(strict_types=1);

namespace App\ReadModel\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_view')]
class OrderViewEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 64)]
    private string $id;

    #[ORM\Column(type: 'string', length: 32, options: ['default' => 'draft'])]
    private string $status = 'draft';

    #[ORM\Column(type: 'string', length: 32, options: ['default' => '0.00'])]
    private string $grandTotal = '0.00';

    #[ORM\Column(type: 'string', length: 32, options: ['default' => '0.00'])]
    private string $paidTotal = '0.00';

    #[ORM\Column(type: 'string', length: 32, options: ['default' => '0.00'])]
    private string $refundedTotal = '0.00';

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getGrandTotal(): string
    {
        return $this->grandTotal;
    }

    public function setGrandTotal(string $grandTotal): void
    {
        $this->grandTotal = $grandTotal;
    }

    public function getPaidTotal(): string
    {
        return $this->paidTotal;
    }

    public function setPaidTotal(string $paidTotal): void
    {
        $this->paidTotal = $paidTotal;
    }

    public function getRefundedTotal(): string
    {
        return $this->refundedTotal;
    }

    public function setRefundedTotal(string $refundedTotal): void
    {
        $this->refundedTotal = $refundedTotal;
    }
}
