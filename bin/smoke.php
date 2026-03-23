#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
use SmartResponsor\Order\Entity\Order\Order;
use SmartResponsor\Order\Service\Order\OrderStateMachine;
use SmartResponsor\Order\Webhook\NoopWebhookDispatcher;
$flow = $argv[2] ?? 'basic';
$sm = new OrderStateMachine(new NoopWebhookDispatcher());
$id = 'ord_' . bin2hex(random_bytes(6));
$o = new Order($id, 1999, 'USD', 'cus_001');
if ($flow==='basic'){ $sm->place($o); $sm->confirm($o); $sm->fulfill($o); $sm->close($o); }
elseif ($flow==='cancel'){ $sm->place($o); $sm->cancel($o); }
elseif ($flow==='return'){ $sm->place($o); $sm->confirm($o); $sm->fulfill($o); $sm->return($o); }
echo json_encode(['orderId'=>$o->id(),'final'=>$o->status()->value])."\n";
