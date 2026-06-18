<?php

class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Benutzer erstellen
    public function createUser(string $username, string $email, string $hashedPassword): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO benutzer (username, email, password)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    // Prüfen ob User bereits existiert
    public function userExists(string $username, string $email): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM benutzer
            WHERE username = ? OR email = ?
            LIMIT 1
        ");

        $stmt->execute([$username, $email]);

        return (bool) $stmt->fetch();
    }

    // User für Login holen (per Email)
    public function getUserByEmail(string $email)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM benutzer
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // User per ID holen
    public function getUserById(int $id)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM benutzer
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Prüfen ob Username bei anderem User existiert
    public function usernameExistsForOther(string $username, int $currentUserId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM benutzer
            WHERE username = ? AND id != ?
            LIMIT 1
        ");
        $stmt->execute([$username, $currentUserId]);
        return (bool)$stmt->fetch();
    }

    // User aktualisieren (Username und/oder Passwort)
    public function updateUser(int $id, string $username, ?string $hashedPassword): bool
    {
        if ($hashedPassword) {
            $stmt = $this->pdo->prepare("
                UPDATE benutzer
                SET username = ?, password = ?
                WHERE id = ?
            ");
            return $stmt->execute([$username, $hashedPassword, $id]);
        } else {
            $stmt = $this->pdo->prepare("
                UPDATE benutzer
                SET username = ?
                WHERE id = ?
            ");
            return $stmt->execute([$username, $id]);
        }
    }

    // User löschen
    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM benutzer
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
}