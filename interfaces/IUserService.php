<?php
interface IUserService {
    public function signup(string $username, string $email, string $password): bool;
    public function login(string $email, string $password): ?array;
}
