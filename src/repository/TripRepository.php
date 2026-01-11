<?php
require_once 'repository.php';

class TripRepository extends Repository {

    public function addTrip($userId, $data) {
        $query = $this->database->connect()->prepare('
            INSERT INTO trips (user_id, title, description, distance, elevation, date, difficulty, max_elevation)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $query->execute([
            $userId,
            $data['title'],
            $data['description'],
            $data['distance'],
            $data['elevation'],
            $data['date'],
            $data['difficulty'],
            $data['max_elevation']
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

    public function getTrip(int $id) {
        $query = $this->database->connect()->prepare('
            SELECT * FROM trips WHERE id = :id
        ');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    
    }

    public function getStats(int $userId): array {
        $query = $this->database->connect()->prepare('
            SELECT
                COUNT(*) as total_trips,
                COALESCE(SUM(distance), 0) as total_distance,
                COALESCE(SUM(elevation), 0) as total_elevation,
                COALESCE(MAX(max_elevation), 0) as highest_peak
            FROM trips 
            WHERE user_id = :user_id
        ');

        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTrip(int $id, array $data) {
        $query = $this->database->connect()->prepare('
            UPDATE trips 
            SET title = :title, description = :description, date = :date, difficulty = :difficulty 
            WHERE id = :id
        ');
        $query->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $query->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $query->bindParam(':date', $data['date'], PDO::PARAM_STR);
        $query->bindParam(':difficulty', $data['difficulty'], PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    public function deleteTrip(int $id, int $userId) {
        $query = $this->database->connect()->prepare('
            DELETE FROM trips WHERE id = :id AND user_id = :user_id
        ');

        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam('user_id', $userId, PDO::PARAM_INT);
        $query->execute();
    }
}