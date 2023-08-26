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
use App\Models\Wishlist;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);
$product = new Product($db);
$wishlist = new Wishlist($db);

$reqData = json_decode(file_get_contents('php://input'), true);
require __DIR__ . '/validations/is-logged-in.validation.php';
require __DIR__ . '/validations/toggle-from-wishlist.validation.php';

$wishlist->userId = $user->id;
$wishlist->productId = $product->id;
$singleWishlistItem = $wishlist->readByUserAndProduct();

if ($singleWishlistItem) {
  $wishlist->id = $singleWishlistItem['id'];
  $wishlist->delete();

  http_response_code(200);
  echo json_encode([
    'toggleType' => 'remove',
    'singleWishlistItem' => $singleWishlistItem
  ]);
} else {
  $singleWishlistItem = $wishlist->create();

  http_response_code(200);
  echo json_encode([
    'toggleType' => 'add',
    'singleWishlistItem' => $singleWishlistItem
  ]);
}
?>