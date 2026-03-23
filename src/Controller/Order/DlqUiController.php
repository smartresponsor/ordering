<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Controller\Order;

use App\ControllerInterface\Order\DlqUiControllerInterface;
use App\ServiceInterface\Order\Outbox\DlqServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/order/dlq/ui')]
class DlqUiController extends AbstractController implements DlqUiControllerInterface
{
    public function __construct(private readonly DlqServiceInterface $service)
    {
    }

    #[Route(path: '', name: 'order_dlq_ui', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $topic = $request->query->get('topic');
        $q = $request->query->get('q');
        $limit = max(1, min(200, (int) $request->query->get('limit', 50)));
        $offset = max(0, (int) $request->query->get('offset', 0));
        [$items, $total] = $this->service->getPage($topic ?: null, $q ?: null, $limit, $offset);

        return $this->render('order/dlq/index.html.twig', [
            'items' => $items,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'topic' => $topic,
            'q' => $q,
        ]);
    }
}
