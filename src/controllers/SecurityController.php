<?php

require_once 'AppController.php';
require_once __DIR__. '/../repository/UserRepository.php';

class SecurityController extends AppController {
    
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register() {
        if (!$this->isPost()){
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'] ?? '';

        if ($password !== $password2) {
            return $this->render('register', ['messages' => ['Passwords do not match!']]);
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userRepository->createUser($email, $hashedPassword, $firstName, $lastName);

        return $this->render('login', ['messages'=> ['Registration successful!']]);
    }
}