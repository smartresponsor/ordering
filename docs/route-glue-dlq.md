# Add to public/index.php (DLQ routes)

$router->get('/internal/dlq/list', function() use($response, $pdo){
  $rows = $pdo->query("SELECT id, tenant_id, topic, payload, last_error as error, created_at FROM outbox WHERE attempts >= 5 ORDER BY created_at ASC LIMIT 200")->fetchAll(PDO::FETCH_ASSOC);
  return $response->json($rows, 200);
});

$router->post('/internal/dlq/replay/{id}', function($req, $p) use($response, $pdo){
  $id = $p['id'];
  $stmt = $pdo->prepare('UPDATE outbox SET attempts=0, updated_at=now() WHERE id=:id');
  $stmt->execute([':id'=>$id]);
  return $response->json(['ok'=>true], 200);
});

$router->post('/internal/dlq/drop/{id}', function($req, $p) use($response, $pdo){
  $id = $p['id'];
  $pdo->prepare('DELETE FROM outbox WHERE id=:id')->execute([':id'=>$id]);
  return $response->json(['ok'=>true], 200);
});
