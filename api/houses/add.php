<?php
require_once(__DIR__ . '/../../config/DatabaseConnection.php');
require_once(__DIR__ . '/../../controllers/HouseController.php');
require_once(__DIR__ . '/../Response.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::json(['error' => 'Method not allowed'], 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['address']) || !isset($data['geolocation'])) {
    Response::json(['error' => 'Missing required fields: address and geolocation'], 400);
}

$address = $data['address'];
$geolocation = $data['geolocation'];
$description = isset($data['description']) ? $data['description'] : null;

$houseController = new HouseController();
$houseController->addHouse([
    'address' => $address,
    'geolocation' => $geolocation,
    'description' => $description,
]);
