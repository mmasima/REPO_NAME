<?php
require_once(__DIR__ . '/../../config/DatabaseConnection.php');
require_once(__DIR__ . '/../../controllers/HouseController.php');
require_once(__DIR__ . '/../Response.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Response::json(['error' => 'Method not allowed'], 405);
}

$houseController = new HouseController();

$houseController->getAllHouses();
