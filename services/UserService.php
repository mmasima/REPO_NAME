<?php
require_once __DIR__ . '/../interfaces/IUserService.php';
require_once __DIR__ . '/../config/DatabaseConnection.php';

class UserService implements IUserService {
    private IDatabaseConnection $dbConnection;

    public function __construct(IDatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function signup(string $username, string $email, string $password): bool {
        $conn = $this->dbConnection->connect();
        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        $success = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        return $success;
    }

    public function login(string $email, string $password): ?array {
        $conn = $this->dbConnection->connect();

        // Retrieve user by email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Remove password before returning user info
            unset($user['password']);
            return $user;
        }
        
        return null;
    }
}
