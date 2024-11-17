<?php
$rootPath = dirname(dirname(dirname(__FILE__)));
require_once($rootPath . '/config/DatabaseConnection.php');
require_once($rootPath . '/repositories/UserRepository.php');
require_once($rootPath . '/api/Response.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0); 
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::json(['error' => 'Method not allowed'], 405);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['email']) || !isset($data['password'])) {
    Response::json(['error' => 'Missing credentials'], 400);
}

$db = (new DatabaseConnection());
$userRepository = new UserRepository($db->getConnection());

$user = $userRepository->authenticate($data['email'], $data['password']);
if ($user) {
    Response::json(['message' => 'Login successful', 'user' => $user]);
} else {
    Response::json(['error' => 'Invalid credentials'], 401);
}
