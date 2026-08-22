<?php

declare(strict_types=1);

use App\Ordering\Controller\Api\OrderPayController;
use App\Ordering\Controller\Api\OrderShipController;
use App\Ordering\EventListener\OrderApiRateLimitListener;
use App\Ordering\EventListener\OrderTenantRateLimitListener;
use App\Ordering\Factory\Storage\OrderS3ClientFactory;
use App\Ordering\MessageHandler\OrderEventMessageHandler;
use App\Ordering\Service\Http\Order\AuditRotator;
use App\Ordering\Service\Http\Order\MonologAuditSink;
use App\Ordering\Service\Http\Order\NdjsonAuditSink;
use App\Ordering\Service\Http\Order\S3AuditShipper;
use App\Ordering\Service\Inventory\InMemoryInventoryService;
use App\Ordering\Service\Inventory\Order\InMemoryInventoryGateway;
use App\Ordering\Service\OrderSummaryProvider;
use App\Ordering\Service\Outbox\OutboxMessengerDispatcher;
use App\Ordering\Service\Outbox\OutboxPublisher;
use App\Ordering\Service\Payment\Order\StripeGateway as OrderStripeGateway;
use App\Ordering\Service\Payment\PaymentProcessorService;
use App\Ordering\Service\Payment\StripeGateway;
use App\Ordering\Service\Pricing\Order\CurrencyConversionService;
use App\Ordering\Service\Pricing\Order\DefaultPromotionStrategy;
use App\Ordering\Service\Pricing\Order\FlatPromotionStrategy;
use App\Ordering\Service\Pricing\Order\FlatTaxationStrategy;
use App\Ordering\Service\Pricing\Order\PriceCalculator;
use App\Ordering\Service\Pricing\Order\TaxationConfigLoader;
use App\Ordering\Service\Pricing\Order\VatExclusiveStrategy;
use App\Ordering\Service\Security\Jwt\OrderJwtTenantResolver;
use App\Ordering\Service\Shipment\ShipmentProcessorService;
use App\Ordering\Service\Shipment\UPSCarrier;
use App\Ordering\Service\Workflow\Order\OrderWorkflowService;
use App\Ordering\ServiceInterface\Http\Order\AuditRotateInterface;
use App\Ordering\ServiceInterface\Http\Order\AuditShipInterface;
use App\Ordering\ServiceInterface\Http\Order\AuditSinkInterface;
use App\Ordering\ServiceInterface\Inventory\InventoryServiceInterface;
use App\Ordering\ServiceInterface\Inventory\Order\InventoryGatewayInterface;
use App\Ordering\ServiceInterface\OrderSummaryProviderInterface;
use App\Ordering\ServiceInterface\Payment\Order\PaymentGatewayInterface as OrderPaymentGatewayInterface;
use App\Ordering\ServiceInterface\Payment\PaymentGatewayInterface;
use App\Ordering\ServiceInterface\Shipment\CarrierInterface;
use App\Ordering\State\Api\OrderDataPersister;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $c): void {
    $s = $c->services()->defaults()->autowire()->autoconfigure();

    // Pricing
    $s->set(FlatPromotionStrategy::class);
    $s->set(FlatTaxationStrategy::class);
    $s->set(DefaultPromotionStrategy::class);
    $s->set(VatExclusiveStrategy::class);
    $s->set(TaxationConfigLoader::class)
        ->arg(0, '%kernel.project_dir%/config/taxation.yaml');
    $s->set(CurrencyConversionService::class)
        ->arg(0, '%kernel.project_dir%/config/exchange_rates.yaml');
    $s->set(PriceCalculator::class)
        ->arg('$promotion', service(DefaultPromotionStrategy::class))
        ->arg('$taxation', service(VatExclusiveStrategy::class))
        ->arg('$taxConfig', service(TaxationConfigLoader::class))
        ->arg('$fx', service(CurrencyConversionService::class));

    // Inventory
    $s->set(InMemoryInventoryService::class);
    $s->alias(InventoryServiceInterface::class, InMemoryInventoryService::class);
    $s->set(InMemoryInventoryGateway::class);
    $s->alias(InventoryGatewayInterface::class, InMemoryInventoryGateway::class);

    // Payment
    $s->set(StripeGateway::class);
    $s->alias(PaymentGatewayInterface::class, StripeGateway::class);
    $s->set(OrderStripeGateway::class);
    $s->alias(OrderPaymentGatewayInterface::class, OrderStripeGateway::class);
    $s->set(PaymentProcessorService::class)
        ->arg(0, service(PaymentGatewayInterface::class))
        ->arg(1, new Reference('doctrine.orm.entity_manager'));

    // Rate limiting
    $s->set(OrderJwtTenantResolver::class);
    $s->set(OrderApiRateLimitListener::class)
        ->arg('$orderApiLimiter', new Reference('limiter.api'));
    $s->set(OrderTenantRateLimitListener::class)
        ->arg('$orderApiTenantLimiter', new Reference('limiter.order_api_write'));

    // ShipmentEntity
    $s->set(UPSCarrier::class);
    $s->alias(CarrierInterface::class, UPSCarrier::class);
    $s->set(ShipmentProcessorService::class)
        ->arg(0, service(CarrierInterface::class))
        ->arg(1, new Reference('doctrine.orm.entity_manager'));

    // Outbox + Messenger
    $s->set(OutboxPublisher::class)
        ->public()
        ->arg(0, new Reference('App\Ordering\Repository\Outbox\OutboxMessageRepository'))
        ->arg(1, new Reference('doctrine.orm.entity_manager'))
        ->arg(2, new Reference('messenger.default_bus'));
    $s->set(OutboxMessengerDispatcher::class)
        ->arg(0, new Reference('doctrine.orm.entity_manager'))
        ->arg(1, new Reference('messenger.default_bus'));

    // HTTP audit helpers
    $s->set(OrderS3ClientFactory::class);
    $s->set('app.order_s3_client', stdClass::class)
        ->factory([service(OrderS3ClientFactory::class), 'create']);
    $s->set(AuditRotator::class)
        ->arg(0, '%kernel.project_dir%');
    $s->alias(AuditRotateInterface::class, AuditRotator::class);
    $s->set(MonologAuditSink::class);
    $s->alias(AuditSinkInterface::class, MonologAuditSink::class);
    $s->set(NdjsonAuditSink::class)
        ->arg(0, '%kernel.project_dir%');
    $s->set(S3AuditShipper::class)
        ->arg(0, service('app.order_s3_client'))
        ->arg(1, '%kernel.project_dir%')
        ->arg(2, 'order-audit');
    $s->alias(AuditShipInterface::class, S3AuditShipper::class);

    // Workflow + API
    $s->set(OrderWorkflowService::class)
        ->arg(0, new Reference('workflow.order'))
        ->arg(1, new Reference('doctrine.orm.entity_manager'))
        ->arg(2, service(ShipmentProcessorService::class))
        ->arg(3, service(PaymentProcessorService::class))
        ->arg(4, service(PriceCalculator::class))
        ->arg(5, service(InventoryServiceInterface::class))
        ->arg(6, service(OutboxPublisher::class));

    $s->set(OrderSummaryProvider::class);
    $s->alias(OrderSummaryProviderInterface::class, OrderSummaryProvider::class);

    $s->set(OrderDataPersister::class)->tag('api_platform.state_processor');
    $s->set(OrderPayController::class)->public();
    $s->set(OrderShipController::class)->public();

    // Messenger handler
    $s->set(OrderEventMessageHandler::class)->tag('messenger.message_handler');
};
