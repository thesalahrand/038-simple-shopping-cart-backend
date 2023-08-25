<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(400);
  echo json_encode(['message' => 'Only POST method is allowed']);
  exit();
}

use Rakit\Validation\Validator;

$validator = new Validator();

$reqData = array_map('trim', $reqData);

$validation = $validator->make($reqData, [
  'username' => 'required|alpha_dash|min:3|max:12',
]);

$validation->validate();

if ($validation->fails()) {
  http_response_code(400);
  echo json_encode(['message' => $validation->errors()->firstOfAll()[array_key_first($validation->errors()->firstOfAll())]]);
  exit();
}
?>
