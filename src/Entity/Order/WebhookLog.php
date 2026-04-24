<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'webhook_log')]
class WebhookLog
{
    #[ORM\Id]
    #[ORM\Column(length: 128)]
    private string $key;

    #[ORM\Column(length: 128)]
    private string $eventType;

    #[ORM\Column(type: 'text')]
    private string $payload;

    public function __construct(string $key, string $eventType, string $payload)
    {
        $this->key = $key;
        $this->eventType = $eventType;
        $this->payload = $payload;
    }

    public function key(): string
    {
        return $this->key;
    }
}
