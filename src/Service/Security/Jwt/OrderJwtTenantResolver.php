<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Jwt;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final readonly class OrderJwtTenantResolver
{
    public function __construct(private string $fallbackTenant = 'public')
    {
    }

    public function resolveTenantId(?TokenInterface $token): string
    {
        if (null === $token) {
            return $this->fallbackTenant;
        }

        $user = $token->getUser();
        if (is_object($user) && method_exists($user, 'getClaims')) {
            $claims = (array) $user->getClaims();
            foreach (['tenant', 'tenant_id', 'tenantId'] as $key) {
                $value = $claims[$key] ?? null;
                if (is_string($value) && '' !== $value) {
                    return $value;
                }
            }
        }

        return $this->fallbackTenant;
    }
}
