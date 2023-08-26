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
use App\Models\Wishlist;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);
$wishlist = new Wishlist($db);

require __DIR__ . '/validations/is-logged-in.validation.php';
require __DIR__ . '/validations/read-all-wishlist-products.validation.php';

$wishlist->userId = $user->id;
$allProducts = $wishlist->read();

http_response_code(200);
echo json_encode($allProducts);
?>