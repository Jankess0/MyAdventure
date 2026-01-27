<?php

require_once 'AppController.php';
require_once __DIR__. '/../repository/TripRepository.php';
require_once __DIR__. '/../models/Trip.php';

class TripController extends AppController {

    private $tripRepository;

    public function __construct()
    {
        $this->tripRepository = new TripRepository();
    }

    public function index() {
        $this->checkSession();
        $userId = $_SESSION['user_id'];
        $trips = $this->tripRepository->getTrips($userId);

        $this->render('home', [
            'trips'=> $trips,
            'user' => [
                'firstName' => $_SESSION['firstName'],
                'lastName' => $_SESSION['lastName'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role']
            ]
        ]);
    }

    public function stats() {
        $this->checkSession();
        $userId = $_SESSION['user_id'];

        $stats = $this->tripRepository->getStats($userId);

        $this->render('stats', [
            'stats' => $stats,
            'user' => [
                'firstName' => $_SESSION['firstName'],
                'lastName' => $_SESSION['lastName'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role']
            ]
        ]);
    }

    public function new_trip() {
        $this->checkSession();

        if (!$this->isPost()) {
            $this->render('new_trip', [
                'user' => [
                'firstName' => $_SESSION['firstName'],
                'lastName' => $_SESSION['lastName'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role']
                ]
                
            ]);
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $difficulty = $_POST['difficulty'];
        $distance = $_POST['distance'] ?? 0;
        $elevation = $_POST['elevation'] ?? 0;
        $userId = $_SESSION['user_id'];
        $max_elevation = $_POST['max_elevation'] ?? 0;

        if (empty($title) || empty($distance)) {
            return $this->render('new_trip', ['messages' => ['Upload GPX file and enter the Title']]);
        }
        
        $photoName = null;

        if (isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
            $photoName = time() . '_' . $_FILES['photo']['name'];

            $destination = 'public/uploaded/photos/' . $photoName;
            
            move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
        }

        $data = [
        'title' => $title,
        'description' => $description,
        'distance' => $distance,
        'elevation' => $elevation,
        'date' => $date,
        'difficulty' => $difficulty,
        'max_elevation' => $max_elevation,
        'photo' => $photoName
        ];

        $this->tripRepository->addTrip(
            $userId,
            $data
        );

        header("Location: /home");
        exit();

    }

    public function editTrip() {
        $this->checkSession();

        if (!$this->isPost()) {
            $id = $_GET['id'];
            $trip = $this->tripRepository->getTrip($id);
            
            if (!$trip || $trip->getId() !== $_SESSION['user_id']) {
                header("Location: /home");
                exit();
            }

            return $this->render('new_trip', [
                'trip' => $trip,
                'user' => [
                    'firstName' => $_SESSION['firstName'],
                    'lastName' => $_SESSION['lastName'],
                    'email' => $_SESSION['email']
            ]]);
        }

        $id = $_POST['id'];
        
        $data = [
            'title' => $_POST['title'] ?? null,
            'description' => $_POST['description'] ?? null,
            'date' => $_POST['date'] ?? null,
            'difficulty' => $_POST['difficulty'] ?? null
        ];

        if (empty($data['title'])) {
            $trip = $this->tripRepository->getTrip($id);
            return $this->render('new_trip', [
                'trip' => $trip, 
                'messages' => ['Title cannot be empty!'],
                'user' => [ 
                    'firstName' => $_SESSION['firstName'],
                    'lastName' => $_SESSION['lastName'],
                    'email' => $_SESSION['email']
                ]
            ]);
        }

        $this->tripRepository->updateTrip($id, $data);
        header("Location: /home");
        exit();
    }

    public function deleteTrip() {
        $this->checkSession();

        if (!$this->isPost()) {
            header("Location: /home");
            exit();
        }

        $id = $_POST['id'];
        $userId = $_SESSION['user_id'];

        if ($id) {
            $this->tripRepository->deleteTrip((int)$id, $userId);
        }
        
        header("Location: /home");
        exit();
        
    }

    private function checkSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }
}