<?php
interface IUserRepository {
    public function create(array $userData): ?array;
    public function authenticate(string $email, string $password): ?array;
}