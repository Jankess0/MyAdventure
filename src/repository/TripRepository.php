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

    public function getTrips(int $userId) :array {
        $query = $this->database->connect()->prepare('
        SELECT * FROM trips WHERE user_id = :user_id ORDER BY date DESC
        ');

        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}