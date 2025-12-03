<?php
// api.php?action=read  -> devuelve JSON con temperatura/humedad
$action = $_GET['action'] ?? '';
if ($action === 'read'){
  $script = __DIR__ . '/scripts/read_sensors.py';
  $cmd = escapeshellcmd("/usr/bin/python3 " . $script);
  exec($cmd . " 2>&1", $out, $r);
  if ($r !== 0){
    http_response_code(500);
    echo json_encode(['error'=>implode("\n", $out)]);
    exit;
  }
  header('Content-Type: application/json');
  echo implode("\n", $out);
  exit;
}
http_response_code(400);
echo json_encode(['error'=>'action inválida']);