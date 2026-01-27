<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AdminController extends AppController {
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function users(){
        $this->checkAdmin();

        $users = $this->userRepository->getUsers();

        $this->render('admin_users', [
            'users' => $users,
            'user' => [ 
                'firstName' => $_SESSION['firstName'],
                'lastName' => $_SESSION['lastName'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role']
            ]
        ]);
    }

    public function editUserForm(){
        $this->checkAdmin();

        if (!isset($_GET['id'])) {
            header("Location: /users");
            exit();
        }

        $id = $_GET['id'];
        $userToEdit = $this->userRepository->getUserById($id);

        if (!$userToEdit) {
            header("Location: /users");
            exit();
        }

        $this->render('edit_user', [
            'userToEdit' => $userToEdit, 
            'user' => [
                'firstName' => $_SESSION['firstName'],
                'lastName' => $_SESSION['lastName'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role'] ?? 'user'
            ]
        ]);
    }

    public function editUser() {
        $this->checkAdmin();

        if (!$this->isPost()) {
            header("Location: /users");
            exit();
        }

        $id = $_POST['id'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        if (empty($firstName) || empty($lastName) || empty($role)) {
            header("Location: /users");
            exit();
        }

        $updateData = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'role' => $role
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userRepository->editUser($id, $updateData);

        header("Location: /users");
        exit();
    }


    public function deleteUser(){
        $this->checkAdmin();

        if(!$this->isPost()){
            header("Location: /users");
            exit();
        }

        $id = $_POST['id'];
        $this->userRepository->deleteUser($id);

        header("Location: /users");
        exit();
    }

    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: /home"); 
            exit();
        }
    }
}