<?php

require_once 'AppController.php';

class TripController extends AppController {


    public function index() {
        $this->checkSession();
        $this->render('home');
    }

    public function stats() {
        $this->checkSession();
        $this->render('stats');
    }

    public function add_trip() {
        $this->checkSession();
        $this->render('new_trip');
    }

    private function checkSession() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }
}