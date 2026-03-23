#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use SmartResponsor\Order\Payment\Router\{HealthScore,Quota,DecisionLog,RouterAA};
use SmartResponsor\Order\Payment\Cost\CostModel;
use SmartResponsor\Order\Observability\RouterMetrics;
use SmartResponsor\Order\Payment\PaymentProviderInterface;

class DummyProv implements PaymentProviderInterface {
  public function __construct(private string $name, private float $failPct = 0.0, private int $minMs=10, private int $maxMs=30) {}
  public function authorize(string $orderId, int $amount, string $currency, array $meta=[]): array { usleep(rand($this->minMs,$this->maxMs)*1000); if (rand(1,100) <= $this->failPct*100) throw new RuntimeException('dummy fail'); return ['provider'=>$this->name,'status'=>'ok']; }
  public function capture(string $paymentId, int $amount): array { return ['status'=>'ok']; }
  public function refund(string $paymentId, int $amount): array { return ['status'=>'ok']; }
  public function verifyWebhook(string $payload, string $signatureHeader): bool { return true; }
  public function mapEvent(array $event): array { return ['name'=>'x','payload'=>$event]; }
}

$redis = new Redis(); $redis->connect('127.0.0.1',6379);
$health = new HealthScore($redis);
$quota = new Quota($redis);
$log = new DecisionLog(__DIR__.'/../var/router/decisions.ndjson');
$metrics = new RouterMetrics(__DIR__.'/../var/metrics/router.prom');
$cost = new CostModel(['stripe'=>30,'adyen'=>35,'paypal'=>45], 50);
$region = getenv('REGION') ?: 'us';
$canary = ['provider'=>getenv('CANARY_PROVIDER') ?: 'adyen', 'pct'=>(int)(getenv('CANARY_PCT') ?: 0)];

$router = new RouterAA(
  [
    ['name'=>'stripe','p'=>new DummyProv('stripe', 0.02) ,'w'=>100,'regions'=>['us','eu']],
    ['name'=>'adyen', 'p'=>new DummyProv('adyen', 0.03) ,'w'=>60 ,'regions'=>['us','eu']],
    ['name'=>'paypal','p'=>new DummyProv('paypal',0.05) ,'w'=>30 ,'regions'=>['us']]
  ],
  $health, $quota, $cost, $log, $metrics, $region, $canary, 'tenant_a'
);

$N = (int)(getenv('N') ?: 50);
for ($i=0; $i<$N; $i++){
  try { $router->authorize('ord_'.$i, 1999, 'USD', ['tenant'=>'tenant_a']); echo "."; }
  catch (Throwable $e){ echo "x"; }
}
echo "\nDone\n";
