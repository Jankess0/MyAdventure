<?php

class Routing {
    public static function run(string $path){
        switch($path){
            case 'login':
                include 'public/views/login.html';
                break;
            case 'register':
                include 'public/views/register.html';
                break;
            case 'home':
                include 'public/views/home.html';
                break;
            case 'new_trip':
                include 'public/views/new_trip.html';
                break;
            case 'stats':
                include 'public/views/stats.html';
                break;
            default:
                include 'public/views/404.html';
                break;
        }

    }
}