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
use App\Models\Cart;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);
$cart = new Cart($db);

require __DIR__ . '/validations/is-logged-in.validation.php';
require __DIR__ . '/validations/read-all-cart-products.validation.php';

$cart->userId = $user->id;
$allProducts = $cart->read();

http_response_code(200);
echo json_encode($allProducts);
?>