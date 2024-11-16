<?php
require_once 'UserController.php';

function route($uri, $method) {
    $controller = new UserController();

    switch (true) {
        case $uri === '/signup' && $method === 'POST':
            $controller->signup();
            break;

        case $uri === '/login' && $method === 'POST':
            $controller->login();
            break;

        default:
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
    }
}
