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

    public function login(){
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->render('login', ['messages'=> 'Fill all fields']);
        }

        $userRow = $this->userRepository->getUserByEmail($email);

        if (!$userRow) {
            return $this->render('login', ['messages'=> 'Failed to login']);
        }

        if (!password_verify($password, $userRow['password'])) {
            return $this->render('login', ['messages'=> 'Failed to login']);
        }

        session_start();
        $_SESSION['user_id'] = $userRow['id'];
        $_SESSION['firstName'] = $userRow['firstName'];
        $_SESSION['lastName'] = $userRow['lastName'];
        $_SESSION['email'] = $userRow['email'];

        header("Location: /home");
        exit();
    }

    public function logout() {
    session_start();
    session_unset();
    session_destroy();
    
    header("Location: /login");
    exit();
    }
}
