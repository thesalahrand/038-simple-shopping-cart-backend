<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(400);
  echo json_encode(['message' => 'Only GET method is allowed']);
  exit();
}
?>
