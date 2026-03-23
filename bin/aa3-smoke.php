#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use SmartResponsor\Order\Payment\Router\{HealthScore,Quota2,DecisionLog,RouterAA3};
use SmartResponsor\Order\Payment\Cost\BudgetGuard;
use SmartResponsor\Order\Observability\RouterMetrics;
use SmartResponsor\Order\Payment\PaymentProviderInterface;

class DummyProv implements PaymentProviderInterface {
  public function __construct(private string $name) {}
  public function authorize(string $orderId, int $amount, string $currency, array $meta=[]): array { return ['provider'=>$this->name,'status'=>'ok']; }
  public function capture(string $paymentId, int $amount): array { return ['status'=>'ok']; }
  public function refund(string $paymentId, int $amount): array { return ['status'=>'ok']; }
  public function verifyWebhook(string $payload, string $signatureHeader): bool { return true; }
  public function mapEvent(array $event): array { return ['name'=>'x','payload'=>$event]; }
}

$redis = new Redis(); $redis->connect('127.0.0.1',6379);
$cfg = json_decode(file_get_contents(__DIR__.'/../config/router/quotas-budgets.json'), true);
$health = new HealthScore($redis);
$quota = new Quota2($redis, (int)($cfg['quotas']['default_rpm'] ?? 12000));
$budget = new BudgetGuard($redis, $cfg);
$log = new DecisionLog(__DIR__.'/../var/router/decisions.ndjson');
$metrics = new RouterMetrics(__DIR__.'/../var/metrics/router.prom');

$router = new RouterAA3(
  [
    ['name'=>'stripe','p'=>new DummyProv('stripe'),'w'=>100,'regions'=>['us','eu']],
    ['name'=>'adyen','p'=>new DummyProv('adyen'),'w'=>60,'regions'=>['us','eu']],
    ['name'=>'paypal','p'=>new DummyProv('paypal'),'w'=>30,'regions'=>['us']]
  ],
  $health, $quota, $budget, $cfg, $log, $metrics, 'us', ['provider'=>'adyen','pct'=>0], 'tenant_a'
);

echo "[AA3 smoke] trying 200 ops...\n";
$ok = 0; $fail = 0;
for ($i=0; $i<200; $i++) {
  try { $router->authorize('ord_'.$i, 1999, 'USD', []); $ok++; }
  catch (Throwable $e) { $fail++; }
}
echo "ok=$ok fail=$fail\n";
