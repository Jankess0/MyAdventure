<?php

require_once 'repository.php';

class UserRepository extends Repository {

    public function createUser(string $email, string $password, string $firstName, string $lastName) : void {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, "firstName", "lastName")
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $email,
            $password,
            $firstName,
            $lastName
        ]);
    }
}