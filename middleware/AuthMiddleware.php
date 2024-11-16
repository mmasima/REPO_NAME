<?php
class AuthMiddleware {
    public static function verifySession() {
        session_start();
        if (isset($_SESSION['user'])) {
            return true; // User is authenticated
        }

        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return false;
    }
}
