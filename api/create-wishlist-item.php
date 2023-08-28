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
use App\Models\WishlistItem;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);
$product = new Product($db);
$wishlistItem = new WishlistItem($db);

$reqData = json_decode(file_get_contents('php://input'), true);
require __DIR__ . '/validations/is-logged-in.validation.php';
require __DIR__ . '/validations/create-wishlist-item.validation.php';

$singleWishlistItem = $wishlistItem->create();

http_response_code(200);
echo json_encode($singleWishlistItem);
?>
