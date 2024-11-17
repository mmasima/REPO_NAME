<?php
require_once(__DIR__ . '/config/DatabaseConnection.php');
require_once(__DIR__ . '/repositories/UserRepository.php');
require_once(__DIR__ . '/controllers/HouseController.php');
require_once(__DIR__ . '/api/Response.php');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$db = new DatabaseConnection();
$conn = $db->getConnection();

$userRepository = new UserRepository($conn);
$houseController = new HouseController();

$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

$endpoint = end($path_parts);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

switch ($endpoint) {
    case 'register':
        if ($method !== 'POST') {
            Response::json(['error' => 'Method not allowed'], 405);
        }
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            Response::json(['error' => 'Missing required fields'], 400);
        }
        $user = $userRepository->create($data);
        if ($user) {
            Response::json(['message' => 'User created successfully', 'user' => $user], 201);
        } else {
            Response::json(['error' => 'Failed to create user'], 500);
        }
        break;

    case 'login':
        if ($method !== 'POST') {
            Response::json(['error' => 'Method not allowed'], 405);
        }
        if (!isset($data['email']) || !isset($data['password'])) {
            Response::json(['error' => 'Missing credentials'], 400);
        }
        $user = $userRepository->authenticate($data['email'], $data['password']);
        if ($user) {
            Response::json(['message' => 'Login successful', 'user' => $user]);
        } else {
            Response::json(['error' => 'Invalid credentials'], 401);
        }
        break;

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
