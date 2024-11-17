<?php
require_once(__DIR__ . '/../../config/DatabaseConnection.php');
require_once(__DIR__ . '/../../controllers/HouseController.php');
require_once(__DIR__ . '/../Response.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    Response::json(['error' => 'Method not allowed'], 405);
}

if (!isset($_GET['id'])) {
    Response::json(['error' => 'Missing house ID'], 400);
}

$houseId = intval($_GET['id']); // Convert to an integer for safety

$houseController = new HouseController();
$success = $houseController->deleteHouse($houseId);

if ($success) {
    Response::json(['message' => 'House deleted successfully'], 200);
} else {
    Response::json(['error' => 'Failed to delete house. It may not exist.'], 404);
}
