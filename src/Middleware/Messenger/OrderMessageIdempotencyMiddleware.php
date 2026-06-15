<?php

declare(strict_types=1);

namespace App\Middleware\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class OrderMessageIdempotencyMiddleware implements MiddlewareInterface
{
    private OrderMessageIdempotencyStoreInterface $store;

    public function __construct(OrderMessageIdempotencyStoreInterface|EntityManagerInterface|null $store = null)
    {
        $this->store = $store instanceof OrderMessageIdempotencyStoreInterface ? $store : new InMemoryOrderMessageIdempotencyStore();
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $key = $this->makeKey($envelope->getMessage());
        if ($this->store->has($key)) {
            return $envelope;
        }
        $this->store->put($key);

        return $stack->next()->handle($envelope, $stack);
    }

    private function makeKey(object $message): string
    {
        $data = ['class' => $message::class, 'props' => get_object_vars($message)];

        return hash('sha256', (string) json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
