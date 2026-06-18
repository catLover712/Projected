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

    public function register()
    {
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirm_password'] ?? null;

        if (!$username || !$email || !$password || !$confirmPassword) {
            header("Location: /Projected/views/register.php?error=missing");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /Projected/views/register.php?error=invalid_email");
            exit();
        }

        if ($password !== $confirmPassword) {
            header("Location: /Projected/views/register.php?error=password_mismatch");
            exit();
        }

        if ($this->userModel->userExists($username, $email)) {
            header("Location: /Projected/views/register.php?error=already_exists");
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $success = $this->userModel->createUser(
            $username,
            $email,
            $hashedPassword
        );

        if ($success) {
            header("Location: /Projected/views/login.php?success=registered");
            exit();
        }

        header("Location: /Projected/views/register.php?error=server");
        exit();
    }

    public function login()
    {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            header("Location: /Projected/views/login.php?error=missing");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /Projected/views/login.php?error=invalid_email");
            exit();
        }

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            header("Location: /Projected/views/login.php?error=not_found");
            exit();
        }

        if (!password_verify($password, $user['password'])) {
            header("Location: /Projected/views/login.php?error=wrong_password");
            exit();
        }

        session_start();
        $_SESSION['user_id'] = $user['id'];

        header("Location: /Projected/index.php");
        exit();
    }

    public function updateProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /Projected/views/login.php");
            exit();
        }

        $userId = $_SESSION['user_id'];
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirm_password'] ?? null;

        if (!$username) {
            header("Location: /Projected/views/profile.php?error=missing_username");
            exit();
        }

        if ($this->userModel->usernameExistsForOther($username, $userId)) {
            header("Location: /Projected/views/profile.php?error=username_taken");
            exit();
        }

        $hashedPassword = null;
        if (!empty($password)) {
            if ($password !== $confirmPassword) {
                header("Location: /Projected/views/profile.php?error=password_mismatch");
                exit();
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }

        $success = $this->userModel->updateUser($userId, $username, $hashedPassword);

        if ($success) {
            header("Location: /Projected/views/profile.php?success=updated");
            exit();
        }

        header("Location: /Projected/views/profile.php?error=server");
        exit();
    }

    public function deleteProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            die("Nicht eingeloggt.");
        }

        $userId = $_SESSION['user_id'];

        $success = $this->userModel->deleteUser($userId);

        if ($success) {
            session_destroy();
            header("Location: /Projected/index.php");
            exit();
        }

        die("Fehler beim Löschen des Profils.");
    }
}

/* -------- ENTRY POINT -------- */

$controller = new UserController();

/* LOGOUT (GET REQUEST) */
if (isset($_GET['logout'])) {

    session_start();
    session_destroy();

    header("Location: /Projected/index.php");
    exit();
}

/* DELETE PROFILE (GET REQUEST) */
if (isset($_GET['delete_profile'])) {
    $controller->deleteProfile();
}

/* LOGIN / REGISTER / UPDATE (POST REQUEST) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $controller->updateProfile();
    } elseif (isset($_POST['username'])) {
        $controller->register();
    } else {
        $controller->login();
    }
}