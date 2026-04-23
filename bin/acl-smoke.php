#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$mapPath = __DIR__ . '/../config/acl/role-action-map.json';
$map = json_decode((string)file_get_contents($mapPath), true);
if (!is_array($map)) {
    fwrite(STDERR, "ACL map is invalid\n");
    exit(1);
}

$role = $argv[1] ?? 'csr';
$action = $argv[2] ?? 'order.transition';
$allowed = in_array($action, $map[$role] ?? [], true);

echo $allowed ? "ALLOW\n" : "DENY\n";
