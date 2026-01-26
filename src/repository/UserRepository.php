<?php

require_once 'repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository {

    public function createUser(string $email, string $password, string $firstName, string $lastName) : void {
        $query = $this->database->connect()->prepare('
            INSERT INTO users (email, password, "firstName", "lastName", role)
            VALUES (?, ?, ?, ?, ?)
        ');

        $query->execute([
            $email,
            $password,
            $firstName,
            $lastName,
            'user'
        ]);
    }

    public function getUserByEmail(string $email) : ?User {

        $query = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $query->bindParam(':email', $email);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
        return null;
        }

        return new User(
            $user['email'],
            $user['password'],
            $user['firstName'],
            $user['lastName'],
            $user['role'],
            $user['id']
        );
    }
}