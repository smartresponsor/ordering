<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestEmailController
{
    #[Route('/api/test-email', name: 'api_test_email', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent() ?: '{}', true);
        if (!is_array($data)) {
            $data = [];
        }

        $to = is_string($data['to'] ?? null) ? $data['to'] : 'default@example.com';
        $subject = is_string($data['subject'] ?? null) ? $data['subject'] : 'Test message';
        $message = is_string($data['message'] ?? null) ? $data['message'] : 'Hello from Order!';

        return new JsonResponse([
            'status' => 'stubbed',
            'channel' => 'email',
            'sent_to' => $to,
            'subject' => $subject,
            'message' => $message,
            'note' => 'Mailer transport is not wired in this repository slice.',
        ], Response::HTTP_ACCEPTED);
    }
}
