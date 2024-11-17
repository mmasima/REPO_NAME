<?php
require_once(__DIR__ . '/../interfaces/IUserRepository.php');

class UserRepository implements IUserRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(array $userData): ?array {
        try {
            $username = $userData['username'];
            $email = $userData['email'];
            $password = password_hash($userData['password'], PASSWORD_DEFAULT);
    
            if ($this->checkExistingUser($email, $username)) {
                throw new Error('User Exists');
            }

            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password) 
                VALUES (:username, :email, :password)
            ");
    
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(':email', $email);   
            $stmt->bindParam(":password", $password);
    
            $result = $stmt->execute();

            return [
                'id' => $stmt->insert_id,
                'username' => $username,
                'email' => $email
            ];
        } catch (\Throwable $th) {
            throw $th;
        }        
    }

    public function checkExistingUser(string $email, string $username)
    {
        try {
            $stmt = $this->db->prepare(
                        "SELECT * FROM users WHERE email = :email AND username = :username" 
                    );
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }  

        return false;
    }

    public function authenticate(string $email, string $password): ?array {
        $stmt = $this->db->prepare("
            SELECT DISTINCT id, username, email, password 
            FROM users WHERE email = :email
        ");

        $stmt->bindParam(':email', $email);   
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $user = $result;

        if ($user && password_verify($password, $user[0]['password'])) {
            return $user;
        }

        return null;
    }
}