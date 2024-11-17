<?php
require_once(__DIR__ . '/../../controllers/ImageController.php');
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

parse_str(file_get_contents("php://input"), $data);
$imageId = $data['id'] ?? null;

if (!$imageId) {
    Response::json(['error' => 'Image ID is required'], 400);
}

$imageController = new ImageController();
if ($imageController->deleteImage($imageId)) {
    Response::json(['message' => 'Image deleted successfully']);
} else {
    Response::json(['error' => 'Failed to delete image'], 404);
}
