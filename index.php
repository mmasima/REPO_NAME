<?php
require_once 'config/DatabaseConnection.php';
require_once 'services/HouseService.php';
require_once 'services/ImageService.php';
require_once 'services/UserService.php';

$dbConnection = new DatabaseConnection();
$houseService = new HouseService($dbConnection);
$imageService = new ImageService($dbConnection);
$userService = new UserService($dbConnection);

header("Content-Type: application/json");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        if ($_GET['action'] === 'signup') {
            $success = $userService->signup($data['username'], $data['email'], $data['password']);
            echo json_encode(["success" => $success]);
        
        } elseif ($_GET['action'] === 'login') {
            $user = $userService->login($data['email'], $data['password']);
            if ($user) {
                echo json_encode(["user" => $user]);
            } else {
                http_response_code(401);
                echo json_encode(["error" => "Invalid credentials"]);
            }
        
        } elseif ($_GET['action'] === 'addHouse') {
            $houseId = $houseService->addHouse($data['address'], $data['description'], $data['geolocation']);
            echo json_encode(["houseId" => $houseId]);
        
        } elseif ($_GET['action'] === 'addImage') {
            $success = $imageService->addImage($data['houseId'], $data['url'], $data['description']);
            echo json_encode(["success" => $success]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        
        if ($_GET['action'] === 'deleteHouse') {
            $success = $houseService->deleteHouse($data['houseId']);
            echo json_encode(["success" => $success]);
        
        } elseif ($_GET['action'] === 'deleteImage') {
            $success = $imageService->deleteImage($data['imageId']);
            echo json_encode(["success" => $success]);
        }
        break;
}
