<?php
require_once __DIR__ . '/../interfaces/IUserService.php';
require_once __DIR__ . '/../config/DatabaseConnection.php';

class UserService implements IUserService
{
    private IDatabaseConnection $dbConnection;

    public function __construct(IDatabaseConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function signup(string $username, string $email, string $password): bool
    {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            error_log("Signup failed: Failed to connect to the database.");
            return false;
        }
    
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if ($hashedPassword === false) {
            error_log("Signup failed: Failed to hash password.");
            return false;
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        if (!$stmt) {
            error_log("Signup failed: Failed to prepare SELECT query: " . $conn->error);
            return false;
        }
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            error_log("Signup failed: User with email '$email' or username '$username' already exists.");
            $stmt->close();
            $conn->close();
            return false;
        }

        $stmt->close();
    
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            error_log("Signup failed: Failed to prepare INSERT query: " . $conn->error);
            return false;
        }
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        if (!$stmt->execute()) {
            error_log("Signup failed: " . $stmt->error);
            $stmt->close();
            $conn->close();
            return false;
        }

        $stmt->close();
        $conn->close();

        error_log("Signup successful: User '$username' with email '$email' created.");
        return true;
    }
    
    public function login(string $email, string $password): ?array
    {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            error_log("Login failed: Failed to connect to the database.");
            echo json_encode(["error" => "Failed to connect to the database"]);
            return null;
        }

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        if (!$stmt) {
            error_log("Login failed: Failed to prepare SELECT query: " . $conn->error);
            echo json_encode(["error" => "Failed to prepare query"]);
            return null;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user) {
            error_log("Login failed: No user found with email '$email'.");
            echo json_encode(["error" => "Invalid credentials"]);
            $stmt->close();
            $conn->close();
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            error_log("Login failed: Invalid password for user '$email'.");
            echo json_encode(["error" => "Invalid credentials"]);
            $stmt->close();
            $conn->close();
            return null;
        }

        session_start();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $email,
        ];

        error_log("Login successful: User '$email' logged in.");
        echo json_encode(["message" => "Login successful"]);

        $stmt->close();
        $conn->close();
        return $_SESSION['user'];
    }
}
