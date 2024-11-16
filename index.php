<?php
require_once(__DIR__ . '/config/DatabaseConnection.php');
require_once(__DIR__ . '/repositories\UserRepository.php');
require_once(__DIR__ . '/api/Response.php');

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Initialize database connection
$db = (new DatabaseConnection());
$userRepository = new UserRepository($db->getConnection());

// Get request details
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Parse the path
$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

// Get the endpoint (last part of the path)
$endpoint = end($path_parts);

// Get JSON request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Routes
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

    default:
        Response::json([
            'error' => 'Not found', 
            'path' => $path,
            'endpoint' => $endpoint
        ], 404);
        break;
}