<?php

declare(strict_types=1);

namespace App\Messenger\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class IdempotencyMiddleware implements MiddlewareInterface
{
    private IdempotencyStoreInterface $store;

    public function __construct(IdempotencyStoreInterface|EntityManagerInterface|null $store = null)
    {
        $this->store = $store instanceof IdempotencyStoreInterface ? $store : new InMemoryIdempotencyStore();
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
