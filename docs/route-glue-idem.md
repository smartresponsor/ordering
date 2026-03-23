# Idempotency glue (public/index.php)

$redisCfg = json_decode(file_get_contents(__DIR__.'/../config/outbox/redis.json'), true)['redis'];
$redis = \SmartResponsor\Order\Infra\Redis\RedisFactory::fromConfig($redisCfg);
$ks = new \SmartResponsor\Order\Infra\Idempotency\RedisKeyStore($redis);
$idem = new \SmartResponsor\Order\Http\Middleware\IdempotencyMiddleware($ks, 'Idempotency-Key', 3600);
// $app->use($idem(...)) — примените к маршрутам с переходами/мутациями
