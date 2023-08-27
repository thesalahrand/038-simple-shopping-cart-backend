<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

use App\Config\Config;
use App\Config\Database;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);
$product = new Product($db);
$cart = new Cart($db);

$reqData = json_decode(file_get_contents('php://input'), true);
require __DIR__ . '/validations/is-logged-in.validation.php';
require __DIR__ . '/validations/update-single-cart-product.validation.php';

$cart->productId = $product->id;
$cart->userId = $user->id;

$singleCartItem = $cart->readByUserAndProduct();
$actualOperationType;

switch ($reqData['type']) {
  case 'increase':
    if (!$singleCartItem) {
      $cart->quantity = 1;
      $singleCartItem = $cart->create();
      $actualOperationType = 'created';
    } else {
      $cart->id = $singleCartItem['id'];
      $cart->quantity = $singleCartItem['quantity'] + 1;
      $singleCartItem = $cart->updateQuantity();
      $actualOperationType = 'updated';
    }

    break;
  case 'decrease':
    if (!$singleCartItem) {
      http_response_code(404);
      echo json_encode(['message' => 'Product not exists into the cart']);
      exit();
    }

    $cart->id = $singleCartItem['id'];
    if ($singleCartItem['quantity'] > 1) {
      $cart->quantity = $singleCartItem['quantity'] - 1;
      $singleCartItem = $cart->updateQuantity();
      $actualOperationType = 'updated';
    } else {
      $singleCartItem = $cart->delete();
      $actualOperationType = 'deleted';
    }

    break;
}

http_response_code(200);
echo json_encode([
  'actualOperationType' => $actualOperationType,
  'singleCartItem' => $singleCartItem
]);
?>