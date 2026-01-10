<?php

require_once 'AppController.php';
require_once __DIR__. '/../repository/TripRepository.php';

class TripController extends AppController {

    private $tripRepository;

    public function __construct()
    {
        $this->tripRepository = new TripRepository();
    }

    public function index() {
        $this->checkSession();
        $this->render('home');
    }

    public function stats() {
        $this->checkSession();
        $this->render('stats');
    }

    public function new_trip() {
        $this->checkSession();

        if (!$this->isPost()) {
            $this->render('new_trip');
        }

        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $difficulty = $_POST['difficulty'];
        $distance = $_POST['distance'] ?? 0;
        $elevation = $_POST['elevation'] ?? 0;
        $userId = $_SESSION['user_id'];

        if (empty($title) || empty($distance)) {
            return $this->render('new_trip', ['messages' => ['Upload GPX file and enter the Title']]);
        }

        $data = [
        'title' => $title,
        'description' => $description,
        'distance' => $distance,
        'elevation' => $elevation,
        'date' => $date,
        'difficulty' => $difficulty
        ];

        $this->tripRepository->addTrip(
            $userId,
            $data
        );

        header("Location: /home");
        exit();

    }

    private function checkSession() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }
}