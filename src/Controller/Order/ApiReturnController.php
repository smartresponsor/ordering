<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use ApiPlatform\Symfony\Validator\ValidatorInterface;
use App\ApiResource\Order\ReturnRequestInput;
use App\ApiResource\Order\ReturnRequestOutput;
use App\Service\Order\ReturnWorkflowService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ApiReturnController
{
    public function __construct(
        private ReturnWorkflowService $workflow,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(string $id, Request $request): ReturnRequestOutput
    {
        /** @var ReturnRequestInput $input */
        $input = new ReturnRequestInput();
        $data = json_decode($request->getContent() ?: '{}', true) ?? [];
        $input->orderId = $id;
        $input->amountMinor = (int) ($data['amountMinor'] ?? 0);
        $input->currency = (string) ($data['currency'] ?? 'USD');
        $input->reason = $data['reason'] ?? null;

        $this->validator->validate($input);
        $ret = $this->workflow->createReturnAndRefund($input->orderId, $input->amountMinor, $input->currency, $input->reason);

        return new ReturnRequestOutput($ret->id(), $ret->status());
    }
}
