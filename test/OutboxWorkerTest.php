<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

<?php
namespace SmartResponsor\Order\Test;
use PHPUnit\Framework\TestCase;
use SmartResponsor\Order\Outbox\FileOutboxRepository;
use SmartResponsor\Order\Outbox\OutboxRecord;
use SmartResponsor\Order\Worker\OutboxWorker;
use SmartResponsor\Order\Webhook\NoopWebhookDispatcher;
use SmartResponsor\Order\Telemetry\FileTelemetry;
final class OutboxWorkerTest extends TestCase{
  public function testRetryFlow(): void{
    $dir = sys_get_temp_dir().'/sr_outbox_'.bin2hex(random_bytes(3));
    $repo = new FileOutboxRepository($dir);
    $telemetry = new FileTelemetry($dir.'/metric.json');
    $worker = new OutboxWorker($repo, new NoopWebhookDispatcher(), $telemetry);
    $r = new OutboxRecord('evt_1','order.created',['fail'=>true]); // force failure
    $repo->add($r);
    $worker->runCycle();
    $due = $repo->due();
    $this->assertNotEmpty($due);
    $this->assertSame(1, $due[0].attempt ?? 1);
  }
}
