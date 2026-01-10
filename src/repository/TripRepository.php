<?php
require_once 'repository.php';

class TripRepository extends Repository {

    public function addTrip($userId, $data) {
        $query = $this->database->connect()->prepare('
            INSERT INTO trips (user_id, title, description, distance, elevation, date, difficulty)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');

        $query->execute([
            $userId,
            $data['title'],
            $data['description'],
            $data['distance'],
            $data['elevation'],
            $data['date'],
            $data['difficulty']
        ]);
    }
}