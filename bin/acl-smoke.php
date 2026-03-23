#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Security\Acl\DefaultAccessPolicy;
$map = json_decode(file_get_contents(__DIR__.'/../config/acl/role-action-map.json'), true);
$acl = new DefaultAccessPolicy($map);
echo $acl->allow('csr','order.transition') ? "ALLOW\n" : "DENY\n";
