<?php

require_once 'AppController.php';

class TripController extends AppController {


    public function index() {
        $this->render('home');
    }

    public function stats() {
        $this->render('stats');
    }

    public function add_trip() {
        $this->render('new_trip');
    }
}