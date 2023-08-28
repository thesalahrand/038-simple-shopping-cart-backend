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
use App\Models\CartItem;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);
$product = new Product($db);
$cartItem = new CartItem($db);

$reqData = json_decode(file_get_contents('php://input'), true);
require __DIR__ . '/validations/is-logged-in.validation.php';
require __DIR__ . '/validations/delete-cart-item.validation.php';

$cartItem->id = $singleCartItem['cart_item_id'];
$singleCartItem = $cartItem->delete();

http_response_code(200);
echo json_encode($singleCartItem);
?>
