<?php

require_once 'AppController.php';
require_once __DIR__. '/../repository/UserRepository.php';
require_once __DIR__. '/../models/User.php';

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

        $passwordRegex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';

        $user = $this->userRepository->getUserByEmail($email);
        if ($user) {
            return $this->render('register', ['messages' => ['User with this email already exists!']]);
        }

        if (!preg_match($passwordRegex, $password)) {
            return $this->render('register', ['messages' => [
            'Password must be at least 8 characters long, contain at least one letter, one number and contain at least one special character.'
        ]]);
        }

        if ($password !== $password2) {
            return $this->render('register', ['messages' => ['Passwords do not match!']]);
        }

        if (empty($email) || empty($password) || empty($password2) || empty($firstName) || empty($lastName)) {
            return $this->render('register', ['messages'=> ['Fill all fields']]);
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
            return $this->render('login', ['messages'=> ['Fill all fields']]);
        }

        $user= $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render('login', ['messages'=> ['Field to login']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages'=> ['Failed to login']]);
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['firstName'] = $user->getFirstName();
        $_SESSION['lastName'] = $user->getLastName();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole();

        header("Location: /home");
        exit();
    }

    public function checkEmailIfExists() {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    
    if ($contentType === "application/json") {
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
        
        header('Content-Type: application/json');

        if (isset($decoded['email'])) {
            $user = $this->userRepository->getUserByEmail($decoded['email']);
            echo json_encode(['exists' => $user !== null]);
        } else {
            echo json_encode(['exists' => false]);
        }
        exit(); // Ważne: zatrzymaj skrypt tutaj
    }
}

    public function logout() {
    session_start();
    session_unset();
    session_destroy();
    
    header("Location: /login");
    exit();
    }
}
