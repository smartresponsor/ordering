<?php

declare(strict_types=1);

namespace App\Ordering\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class CustomerOrderActorAccessService
{
    public function __construct(private Security $security)
    {
    }

    public function requireCustomerId(Request $request): string
    {
        $hostCustomerId = trim((string) $request->attributes->get('ordering_customer_id', ''));
        if ('' !== $hostCustomerId) {
            return $hostCustomerId;
        }

        $user = $this->security->getUser();
        if ($user instanceof UserInterface) {
            $customerId = trim($user->getUserIdentifier());
            if ('' !== $customerId) {
                return $customerId;
            }
        }

        throw new AccessDeniedHttpException('Authenticated order customer is required.');
    }
}
