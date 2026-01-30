<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/TripController.php';
require_once 'src/controllers/AdminController.php';

class Routing {

    public static $routes = [
        'login' => ['controller' => 'SecurityController', 'action' => 'login'],
        'register' => ['controller' => 'SecurityController', 'action' => 'register'],
        'logout' => ['controller' => 'SecurityController', 'action' => 'logout'],
        'home' => ['controller' => 'TripController', 'action' => 'index'],
        'stats' => ['controller' => 'TripController', 'action' => 'stats'],
        'new_trip' => ['controller' => 'TripController', 'action' => 'new_trip'],
        'delete_trip' => ['controller' => 'TripController', 'action' => 'deleteTrip'],
        'edit_trip' => ['controller' => 'TripController', 'action' => 'editTrip'],
        'users' => ['controller' => 'AdminController', 'action' => 'users'],
        'delete_user' => ['controller' => 'AdminController', 'action' => 'deleteUser'],
        'edit_user_form' => ['controller' => 'AdminController', 'action' => 'editUserForm'],
        'edit_user' => ['controller' => 'AdminController', 'action' => 'editUser'],
        'check_email_exists' => ['controller' => 'SecurityController', 'action' => 'checkEmailIfExists']

    ];
    
    public static function run(string $path){
        $urlParts = explode("/", $path);
        $action = $urlParts[0];

        if (!array_key_exists($action, self::$routes)) {
            include 'public/views/404.html';
            return;
        }

        $controller = self::$routes[$action]['controller'];
        $method = self::$routes[$action]['action'];

        $controllerObj = new $controller;
        $controllerObj->$method();
    }
}