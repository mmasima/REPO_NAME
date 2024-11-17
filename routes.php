<?php
require_once(__DIR__ . '/controllers/HouseController.php');
require_once(__DIR__ . '/api/Response.php');

$houseController = new HouseController();

switch ($endpoint) {
    case 'add-house':
        if ($method !== 'POST') {
            Response::json(['error' => 'Method not allowed'], 405);
        }
        $houseController->addHouse($data);
        break;

    case 'delete-house':
        if ($method !== 'DELETE') {
            Response::json(['error' => 'Method not allowed'], 405);
        }
        if (!isset($path_parts[2])) {
            Response::json(['error' => 'House ID is required'], 400);
        }
        $houseId = intval($path_parts[2]);
        $houseController->deleteHouse($houseId);
        break;

    case 'get-houses':
        if ($method !== 'GET') {
            Response::json(['error' => 'Method not allowed'], 405);
        }
        $houseController->getAllHouses();
        break;

    default:
        Response::json(['error' => 'Not found'], 404);
        break;
}
