<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        global $pdo;
        $this->userModel = new UserModel($pdo);
    }

    /* ---------------- REGISTER ---------------- */

    public function register()
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Required fields
        if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
            $this->redirect('register', 'missing');
        }

        // Username validation (C6 improvement)
        if (strlen($username) < 3 || strlen($username) > 15) {
            $this->redirect('register', 'invalid_username');
        }

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 50) {
            $this->redirect('register', 'invalid_email');
        }

        // Password validation
        if (strlen($password) < 6) {
            $this->redirect('register', 'weak_password');
        }

        // Password match
        if ($password !== $confirmPassword) {
            $this->redirect('register', 'password_mismatch');
        }

        // User exists check
        if ($this->userModel->userExists($username, $email)) {
            $this->redirect('register', 'already_exists');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $success = $this->userModel->createUser(
            $username,
            $email,
            $hashedPassword
        );

        if ($success) {
            $this->redirect('login', 'registered', true);
        }

        $this->redirect('register', 'server');
    }

    /* ---------------- LOGIN ---------------- */

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $this->redirect('login', 'missing');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('login', 'invalid_email');
        }

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            $this->redirect('login', 'not_found');
        }

        if (!password_verify($password, $user['password'])) {
            $this->redirect('login', 'wrong_password');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];

        header("Location: /Projected/index.php");
        exit();
    }

    /* ---------------- UPDATE PROFILE ---------------- */

    public function updateProfile()
    {
        $this->ensureSession();

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $userId = $_SESSION['user_id'];
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($username === '') {
            $this->redirect('profile', 'missing_username');
        }

        if (strlen($username) < 3 || strlen($username) > 15) {
            $this->redirect('profile', 'invalid_username');
        }

        if ($this->userModel->usernameExistsForOther($username, $userId)) {
            $this->redirect('profile', 'username_taken');
        }

        $hashedPassword = null;

        if (!empty($password)) {

            if (strlen($password) < 6) {
                $this->redirect('profile', 'weak_password');
            }

            if ($password !== $confirmPassword) {
                $this->redirect('profile', 'password_mismatch');
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }

        $success = $this->userModel->updateUser($userId, $username, $hashedPassword);

        if ($success) {
            $this->redirect('profile', 'updated', true);
        }

        $this->redirect('profile', 'server');
    }

    /* ---------------- DELETE PROFILE ---------------- */

    public function deleteProfile()
    {
        $this->ensureSession();

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $userId = $_SESSION['user_id'];

        $success = $this->userModel->deleteUser($userId);

        if ($success) {
            session_destroy();
            header("Location: /Projected/index.php");
            exit();
        }

        $this->redirect('profile', 'server');
    }

    /* ---------------- HELPERS ---------------- */

    private function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function redirect(string $page, ?string $error = null, bool $success = false): void
    {
        $base = "/Projected/views/$page.php";

        if ($success) {
            header("Location: $base?success=$error");
        } else {
            header("Location: $base?error=$error");
        }

        exit();
    }
}

/* ---------------- ENTRY POINT ---------------- */

$controller = new UserController();

/* LOGOUT */
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();

    header("Location: /Projected/index.php");
    exit();
}

/* DELETE PROFILE */
if (isset($_GET['delete_profile'])) {
    $controller->deleteProfile();
}

/* POST ROUTING */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $controller->updateProfile();
    } elseif (isset($_POST['username'])) {
        $controller->register();
    } else {
        $controller->login();
    }
}