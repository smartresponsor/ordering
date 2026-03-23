# Glue: интеграция RouterAA

```php
$redis = new Redis(); $redis->connect('127.0.0.1',6379);
$health = new \SmartResponsor\Order\Payment\Router\HealthScore($redis);
$quota = new \SmartResponsor\Order\Payment\Router\Quota($redis);
$cost = new \SmartResponsor\Order\Payment\Cost\CostModel(['stripe'=>30,'adyen'=>35,'paypal'=>45], 50);
$log = new \SmartResponsor\Order\Payment\Router\DecisionLog(__DIR__.'/../var/router/decisions.ndjson');
$metrics = new \SmartResponsor\Order\Observability\RouterMetrics(__DIR__.'/../var/metrics/router.prom');
$region = getenv('REGION') ?: 'us';
$canary = ['provider'=>'adyen','pct'=>5];

$router = new \SmartResponsor\Order\Payment\Router\RouterAA(
  [
    ['name'=>'stripe','p'=>$stripeProvider,'w'=>100,'regions'=>['us','eu']],
    ['name'=>'adyen', 'p'=>$adyenProvider ,'w'=>60 ,'regions'=>['us','eu']],
    ['name'=>'paypal','p'=>$paypalProvider,'w'=>30 ,'regions'=>['us']]
  ],
  $health, $quota, $cost, $log, $metrics, $region, $canary, $tenantId
);
```
