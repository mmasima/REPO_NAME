<?php
require_once(__DIR__ . '/../../config/DatabaseConnection.php');
require_once(__DIR__ . '/../../controllers/ImageController.php');
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

$houseId = $_POST['house_id'] ?? null;
$description = $_POST['description'] ?? '';

if (!$houseId || !isset($_FILES['image'])) {
    Response::json(['error' => 'House ID and image file are required'], 400);
}

$targetDir = __DIR__ . '/../../uploads/';
$fileName = basename($_FILES['image']['name']);
$targetFilePath = $targetDir . $fileName;

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
    $imageController = new ImageController();
    $imageController->addImage($houseId, $fileName, $description);
    Response::json(['message' => 'Image uploaded successfully']);
} else {
    $error = error_get_last();
    file_put_contents('debug_log.txt', "Upload error:\n" . print_r($error, true), FILE_APPEND);
    Response::json(['error' => 'Failed to upload image'], 500);
}

