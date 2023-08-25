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
use Firebase\JWT\JWT;

Config::init();
$database = new Database();
$db = $database->connect();

$user = new User($db);

$reqData = json_decode(file_get_contents('php://input'), true);
require __DIR__ . '/validations/login.validation.php';

$user->username = $reqData['username'];
$singleUser = $user->readByUsername();

if (!$singleUser) {
  $singleUser = $user->create();
}

$payload = ['id' => $singleUser['id']];
$jwt = JWT::encode($payload, $_ENV['JWT_SECRET_KEY'], 'HS256');
$singleUser['jwt'] = $jwt;

http_response_code(200);
echo json_encode($singleUser);
?>
