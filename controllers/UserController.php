<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private IUserService $userService;

    public function __construct(IUserService $userService) {
        $this->userService = $userService;
    }

    public function signUp($firstName, $lastName, $email, $password) {
        if ($this->userService->findUserByEmail($email)) {
            echo "Email already exists.";
            return;
        }
        $this->userService->createUser($firstName, $lastName, $email, $password);
        header("Location: index.php");
        exit();
    }

    public function signIn($email, $password) {
        $user = $this->userService->findUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['email'] = $user['email'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "Incorrect email or password";
        }
    }
}
