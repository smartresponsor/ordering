<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Security\Jwt\JwtTenantResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class TenantKeyResolver
{
    public function __construct(
        private RequestStack $rs,
        private TokenStorageInterface $ts,
        private JwtTenantResolver $tenantResolver,
    ) {
    }

    public function key(): string
    {
        $req = $this->rs->getCurrentRequest();
        $ip = $req?->getClientIp() ?? '0.0.0.0';
        $token = $this->ts->getToken();
        $tenant = $this->tenantResolver->resolveTenantId($token);
        $userId = method_exists($token?->getUser(), 'getUserIdentifier') ? $token->getUser()->getUserIdentifier() : 'anon';

        return sprintf('%s:%s:%s', $tenant, $userId, $ip);
    }
}
