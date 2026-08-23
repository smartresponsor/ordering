<?php

declare(strict_types=1);

namespace App\Ordering\Voter\Jwt;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class OrderJwtScopeVoter extends Voter
{
    public const string ATTRIBUTE = 'JWT_SCOPE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ATTRIBUTE === $attribute || 'jwt_scope' === strtolower($attribute);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $scope = is_string($subject) && '' !== $subject ? $subject : null;
        if (null === $scope) {
            return true;
        }

        $user = $token->getUser();
        $claims = is_object($user) && method_exists($user, 'getClaims') ? (array) $user->getClaims() : [];
        $scopes = $claims['scope'] ?? $claims['scopes'] ?? [];

        if (is_string($scopes)) {
            $scopes = preg_split('/\s+/', trim($scopes)) ?: [];
        }

        return in_array($scope, $scopes, true);
    }
}
