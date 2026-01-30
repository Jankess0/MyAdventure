<?php
require_once 'repository.php';

class TripRepository extends Repository {

    public function addTrip($userId, $data) {
        $query = $this->database->connect()->prepare('
            INSERT INTO trips (user_id, title, description, distance, elevation, date, difficulty, max_elevation, photo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');

        $query->execute([
            $userId,
            $data['title'],
            $data['description'],
            $data['distance'],
            $data['elevation'],
            $data['date'],
            $data['difficulty'],
            $data['max_elevation'],
            $data['photo']
        ]);
    }

    public function getTrips(int $userId) :array {
        $query = $this->database->connect()->prepare('
        SELECT * FROM trips WHERE user_id = :user_id ORDER BY date DESC
        ');

        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();

        $trips = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($trips as $trip) {
            $result[] = new Trip(
                $trip['id'],
                $trip['user_id'],
                $trip['title'],
                $trip['description'],
                $trip['distance'],
                $trip['elevation'],
                $trip['date'],
                $trip['difficulty'],
                $trip['max_elevation'],
                $trip['photo']
            );
        }

        return $result;
    }

    public function getTrip(int $id): ?Trip {
        $query = $this->database->connect()->prepare('
            SELECT * FROM trips WHERE id = :id
        ');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $trip = $query->fetch(PDO::FETCH_ASSOC);

        if ($trip === false){
            return null;
        }

        return new Trip(
            $trip['id'],
            $trip['user_id'],
            $trip['title'],
            $trip['description'],
            $trip['distance'],
            $trip['elevation'],
            $trip['date'],
            $trip['difficulty'],
            $trip['max_elevation'],
            $trip['photo']
        );
    
    }

    public function getStats(int $userId): array {
        $query = $this->database->connect()->prepare('
            SELECT * FROM user_stats_view WHERE user_id = :user_id
        ');

        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return [
                'total_trips' => 0, 
                'total_distance' => 0, 
                'total_elevation' => 0, 
                'highest_peak' => 0
            ];
        }
        
        return $result;

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