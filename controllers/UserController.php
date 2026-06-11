<?php

class UserController
{
    public function register()
    {
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirm_password'] ?? null;

        if (!$username || !$email || !$password || !$confirmPassword) {
            die("Fehlende Eingaben");
        }

        if ($password !== $confirmPassword) {
            die("Passwörter stimmen nicht überein");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Ungültige E-Mail");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


        echo "Registrierung erfolgreich für: " . htmlspecialchars($username);
    }

    public function login()
    {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            die("Fehlende Eingaben");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Ungültige E-Mail");
        }

        // ---- MOCK USER (zum Testen ohne DB) ----
        $mockUser = [
            'email' => 'test@test.com',
            'password' => password_hash('1234', PASSWORD_DEFAULT)
        ];

        if ($email !== $mockUser['email']) {
            die("User nicht gefunden");
        }

        if (!password_verify($password, $mockUser['password'])) {
            die("Falsches Passwort");
        }

        session_start();
        $_SESSION['user'] = $email;

        echo "Login erfolgreich!";
    }
}


$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['username'])) {
        $controller->register();
    } 

    else {
        $controller->login();
    }
}