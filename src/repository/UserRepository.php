<?php

require_once 'repository.php';

class UserRepository extends Repository {

    public function createUser(string $email, string $password, string $firstName, string $lastName) : void {
        $query = $this->database->connect()->prepare('
            INSERT INTO users (email, password, "firstName", "lastName")
            VALUES (?, ?, ?, ?)
        ');

        $query->execute([
            $email,
            $password,
            $firstName,
            $lastName
        ]);
    }

    public function getUserByEmail(string $email) : ?array {

        $query = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $query->bindParam(':email', $email);
        $query->execute();

        $users = $query->fetch(PDO::FETCH_ASSOC);

        return $users;
    }
}