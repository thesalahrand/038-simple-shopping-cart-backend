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
  'productId' => 'required|integer|min:1',
]);

$validation->validate();

if ($validation->fails()) {
  http_response_code(400);
  echo json_encode(['message' => $validation->errors()->firstOfAll()[array_key_first($validation->errors()->firstOfAll())]]);
  exit();
}

$product->id = $reqData['productId'];
$singleProduct = $product->readById();

if (!$singleProduct) {
  http_response_code(404);
  echo json_encode(['message' => 'Product not found']);
  exit();
}

$cartItem->productId = $reqData['productId'];
$cartItem->userId = $user->id;
$singleCartItem = $cartItem->readByUserAndProduct();

if (!$singleCartItem) {
  http_response_code(404);
  echo json_encode(['message' => 'Product not exists into the cart']);
  exit();
}
?>