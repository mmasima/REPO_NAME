<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private IUserService $userService;

    public function __construct(IUserService $userService) {
        $this->userService = $userService;
    }

    public function signup(string $username, string $email, string $password): bool
    {
        return $this->userService->signup($username, $email, $password);
    }

    public function login(string $email, string $password): ?array
    {
        return $this->userService->login($email, $password);
    }
}