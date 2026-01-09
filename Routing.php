<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/TripController.php';

class Routing {

    public static $routes = [
        'login' => ['controller' => 'SecurityController', 'action' => 'login'],
        'register' => ['controller' => 'SecurityController', 'action' => 'register'],
        'home' => ['controller' => 'TripController', 'action' => 'index'],
        'stats' => ['controller' => 'TripController', 'action' => 'stats'],
        'add_trip' => ['controller' => 'TripController', 'action' => 'add_trip']
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