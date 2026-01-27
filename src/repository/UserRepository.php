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

    public function getUserById(int $id): ?User {
        $query = $this->database->connect()->prepare('
            SELECT * FROM users WHERE id = :id'
        );

    $query->bindParam(':id', $id, PDO::PARAM_INT);
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

    public function getUsers(): array {
        $query = $this->database->connect()->prepare('
            SELECT * FROM users ORDER BY id ASC
        ');

        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($users as $user) {
            $result[] = new User(
                $user['email'],
                $user['password'],
                $user['firstName'],
                $user['lastName'],
                $user['role'],
                $user['id']
            );
        }
        return $result;
    }

    public function editUser(int $id, array $data): void {
        if (isset($data['password'])) {
            $query = $this->database->connect()->prepare('
                UPDATE users 
                SET "firstName" = :firstName, "lastName" = :lastName, role = :role, password = :password
                WHERE id = :id
            ');
            $query->bindParam(':password', $data['password']);
        } else {
            $query = $this->database->connect()->prepare('
                UPDATE users 
                SET "firstName" = :firstName, "lastName" = :lastName, role = :role 
                WHERE id = :id
            ');
        }

        $query->bindParam(':firstName', $data['firstName']);
        $query->bindParam(':lastName', $data['lastName']);
        $query->bindParam(':role', $data['role']);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        
        $query->execute();
    }

    public function deleteUser(int $id): void {
        $query = $this->database->connect()->prepare('
            DELETE FROM users WHERE id = :id
        ');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }
}