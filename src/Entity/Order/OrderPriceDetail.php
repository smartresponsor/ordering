<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use App\ValueObject\Order\Currency;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'order_price_detail')]
class OrderPriceDetail
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'bigint')]
    private string $subtotalMinor = '0';

    #[ORM\Column(type: 'bigint')]
    private string $discountMinor = '0';

    #[ORM\Column(type: 'bigint')]
    private string $taxMinor = '0';

    #[ORM\Column(type: 'bigint')]
    private string $totalMinor = '0';

    public function __construct(string $id, Currency $currency, int $subtotalMinor, int $discountMinor, int $taxMinor, int $totalMinor)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->id = $id;
        $this->currency = $currency->code();
        $this->subtotalMinor = (string) $subtotalMinor;
        $this->discountMinor = (string) $discountMinor;
        $this->taxMinor = (string) $taxMinor;
        $this->totalMinor = (string) $totalMinor;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function currency(): Currency
    {
        return new Currency($this->currency);
    }

    public function subtotalMinor(): int
    {
        return (int) $this->subtotalMinor;
    }

    public function discountMinor(): int
    {
        return (int) $this->discountMinor;
    }

    public function taxMinor(): int
    {
        return (int) $this->taxMinor;
    }

    public function totalMinor(): int
    {
        return (int) $this->totalMinor;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): self
    {
        $this->id = $id;

        return $this;
    }
}
