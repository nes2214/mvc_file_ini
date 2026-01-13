<?php

class User {
    private $username;
    private $password;
    private $age;
    private $role;
    private $active;

    public function __construct($username = "", $password = "", $age = 0, $role = "", $active = 1) {
        $this->username = $username;
        $this->password = $password;
        $this->age = $age;
        $this->role = $role;
        $this->active = $active;
    }

    // Getters
    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getAge(): int {
        return $this->age;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function isActive(): int {
        return $this->active;
    }

    // Setters
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setAge(int $age): void {
        $this->age = $age;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function setActive(int $active): void {
        $this->active = $active;
    }
}