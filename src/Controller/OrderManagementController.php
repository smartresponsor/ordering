<?php

declare(strict_types=1);

namespace App\Ordering\Controller;

use App\Ordering\DTO\OrderCreateDTO;
use App\Ordering\DTO\OrderPaymentDTO;
use App\Ordering\DTO\OrderRefundDTO;
use App\Ordering\DTO\OrderShipmentDTO;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Form\OrderCreateType;
use App\Ordering\Form\OrderPaymentType;
use App\Ordering\Form\OrderRefundType;
use App\Ordering\Form\OrderShipmentType;
use App\Ordering\Repository\Order\OrderRepository;
use App\Ordering\Service\OrderManagementSurfaceContractFactory;
use App\Ordering\ValueObject\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manage/orders')]
final class OrderManagementController extends AbstractController
{
    public function __construct(
        private readonly OrderManagementSurfaceContractFactory $surfaceContractFactory,
    ) {
    }

    #[Route('', name: 'order_management_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): mixed
    {
        $createDto = new OrderCreateDTO();
        $createForm = $this->createForm(OrderCreateType::class, $createDto);
        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            $order = new OrderEntity($createDto->currency, $createDto->grandTotal);
            $order->setStatus(OrderStatus::Placed);
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', sprintf('Order %s created.', $order->slug()));

            return $this->redirectToRoute('order_management_index');
        }

        /** @var OrderRepository $repo */
        $repo = $em->getRepository(OrderEntity::class);
        $orders = $repo->findBy([], ['createdAt' => 'DESC']);
        $paymentForms = [];
        $shipmentForms = [];
        $refundForms = [];

        foreach ($orders as $order) {
            $paymentForms[$order->slug()] = $this->createForm(OrderPaymentType::class, new OrderPaymentDTO(), [
                'action' => $this->generateUrl('order_management_pay', ['id' => $order->slug()]),
            ])->createView();
            $shipmentForms[$order->slug()] = $this->createForm(OrderShipmentType::class, new OrderShipmentDTO(), [
                'action' => $this->generateUrl('order_management_ship', ['id' => $order->slug()]),
            ])->createView();
            $refundForms[$order->slug()] = $this->createForm(OrderRefundType::class, new OrderRefundDTO(), [
                'action' => $this->generateUrl('order_management_refund', ['id' => $order->slug()]),
            ])->createView();
        }

        return $this->surfaceContractFactory->createIndexSurface(
            $orders,
            $createForm->createView(),
            $paymentForms,
            $shipmentForms,
            $refundForms,
        );
    }

    #[Route('/{id}/pay', name: 'order_management_pay', methods: ['POST'])]
    public function pay(string $id, Request $request, EntityManagerInterface $em): Response
    {
        /** @var OrderRepository $repo */
        $repo = $em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($id);
        if (!$order instanceof OrderEntity) {
            $this->addFlash('danger', 'Order not found.');

            return $this->redirectToRoute('order_management_index');
        }

        $dto = new OrderPaymentDTO();
        $form = $this->createForm(OrderPaymentType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment = $order->applyPayment($dto->amount, $dto->externalRef);
            $em->persist($payment);
            $em->flush();
            $this->addFlash('success', sprintf('Payment captured for %s.', $order->slug()));
        } else {
            $this->addFlash('danger', 'Payment form contains invalid data.');
        }

        return $this->redirectToRoute('order_management_index');
    }

    #[Route('/{id}/ship', name: 'order_management_ship', methods: ['POST'])]
    public function ship(string $id, Request $request, EntityManagerInterface $em): Response
    {
        /** @var OrderRepository $repo */
        $repo = $em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($id);
        if (!$order instanceof OrderEntity) {
            $this->addFlash('danger', 'Order not found.');

            return $this->redirectToRoute('order_management_index');
        }

        $dto = new OrderShipmentDTO();
        $form = $this->createForm(OrderShipmentType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shipment = $order->ship($dto->carrier, null, $dto->note);
            $em->persist($shipment);
            $em->flush();
            $this->addFlash('success', sprintf('Order %s shipped.', $order->slug()));
        } else {
            $this->addFlash('danger', 'ShipmentEntity form contains invalid data.');
        }

        return $this->redirectToRoute('order_management_index');
    }

    #[Route('/{id}/refund', name: 'order_management_refund', methods: ['POST'])]
    public function refund(string $id, Request $request, EntityManagerInterface $em): Response
    {
        /** @var OrderRepository $repo */
        $repo = $em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($id);
        if (!$order instanceof OrderEntity) {
            $this->addFlash('danger', 'Order not found.');

            return $this->redirectToRoute('order_management_index');
        }

        $dto = new OrderRefundDTO();
        $form = $this->createForm(OrderRefundType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $refund = $order->refund($dto->amount, $dto->reason);
            $em->persist($refund);
            $em->flush();
            $this->addFlash('success', sprintf('Refund registered for %s.', $order->slug()));
        } else {
            $this->addFlash('danger', 'Refund form contains invalid data.');
        }

        return $this->redirectToRoute('order_management_index');
    }
}
