<?php

declare(strict_types=1);

namespace App\RateLimiter;

use App\Security\Jwt\JwtTenantResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final readonly class TenantKeyResolver
{
    public function __construct(
        private RequestStack $requestStack,
        private TokenStorageInterface $tokenStorage,
        private JwtTenantResolver $tenantResolver,
    ) {
    }

    public function key(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request?->getClientIp() ?? '0.0.0.0';
        $token = $this->tokenStorage->getToken();
        $tenant = $this->tenantResolver->resolveTenantId($token);
        $user = $token?->getUser();
        $userId = is_object($user) && method_exists($user, 'getUserIdentifier') ? $user->getUserIdentifier() : 'anon';

        return sprintf('%s:%s:%s', $tenant, $userId, $ip);
    }
}
