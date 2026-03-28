<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\OrderCreateDTO;
use App\DTO\OrderPaymentDTO;
use App\DTO\OrderRefundDTO;
use App\DTO\OrderShipmentDTO;
use App\Entity\Order;
use App\Form\OrderCreateType;
use App\Form\OrderPaymentType;
use App\Form\OrderRefundType;
use App\Form\OrderShipmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manage/orders')]
final class OrderManagementController extends AbstractController
{
    #[Route('', name: 'order_management_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $createDto = new OrderCreateDTO();
        $createForm = $this->createForm(OrderCreateType::class, $createDto);
        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $order = new Order($createDto->currency, $createDto->grandTotal);
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', sprintf('Order %s created.', $order->getId()));

            return $this->redirectToRoute('order_management_index');
        }

        $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC']);
        $paymentForms = [];
        $shipmentForms = [];
        $refundForms = [];

        foreach ($orders as $order) {
            $paymentForms[$order->getId()] = $this->createForm(OrderPaymentType::class, new OrderPaymentDTO(), [
                'action' => $this->generateUrl('order_management_pay', ['id' => $order->getId()]),
            ])->createView();
            $shipmentForms[$order->getId()] = $this->createForm(OrderShipmentType::class, new OrderShipmentDTO(), [
                'action' => $this->generateUrl('order_management_ship', ['id' => $order->getId()]),
            ])->createView();
            $refundForms[$order->getId()] = $this->createForm(OrderRefundType::class, new OrderRefundDTO(), [
                'action' => $this->generateUrl('order_management_refund', ['id' => $order->getId()]),
            ])->createView();
        }

        return $this->render('order_management/index.html.twig', [
            'create_form' => $createForm->createView(),
            'orders' => $orders,
            'payment_forms' => $paymentForms,
            'shipment_forms' => $shipmentForms,
            'refund_forms' => $refundForms,
        ]);
    }

    #[Route('/{id}/pay', name: 'order_management_pay', methods: ['POST'])]
    public function pay(Order $order, Request $request, EntityManagerInterface $em): Response
    {
        $dto = new OrderPaymentDTO();
        $form = $this->createForm(OrderPaymentType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment = $order->applyPayment($dto->amount, $dto->externalRef);
            $em->persist($payment);
            $em->flush();
            $this->addFlash('success', sprintf('Payment captured for %s.', $order->getId()));
        } else {
            $this->addFlash('danger', 'Payment form contains invalid data.');
        }

        return $this->redirectToRoute('order_management_index');
    }

    #[Route('/{id}/ship', name: 'order_management_ship', methods: ['POST'])]
    public function ship(Order $order, Request $request, EntityManagerInterface $em): Response
    {
        $dto = new OrderShipmentDTO();
        $form = $this->createForm(OrderShipmentType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipment = $order->ship($dto->carrier, null, $dto->note);
            $em->persist($shipment);
            $em->flush();
            $this->addFlash('success', sprintf('Order %s shipped.', $order->getId()));
        } else {
            $this->addFlash('danger', 'Shipment form contains invalid data.');
        }

        return $this->redirectToRoute('order_management_index');
    }

    #[Route('/{id}/refund', name: 'order_management_refund', methods: ['POST'])]
    public function refund(Order $order, Request $request, EntityManagerInterface $em): Response
    {
        $dto = new OrderRefundDTO();
        $form = $this->createForm(OrderRefundType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $refund = $order->refund($dto->amount, $dto->reason);
            $em->persist($refund);
            $em->flush();
            $this->addFlash('success', sprintf('Refund registered for %s.', $order->getId()));
        } else {
            $this->addFlash('danger', 'Refund form contains invalid data.');
        }

        return $this->redirectToRoute('order_management_index');
    }
}
