<?php

require_once __DIR__ . '/../config/database.php';

class ProjectModel
{
    private PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllProjects(): array
    {
        $stmt = $this->pdo->query("
            SELECT eintrag.*, benutzer.username AS owner_name
            FROM eintrag
            LEFT JOIN benutzer ON eintrag.fk_user_id = benutzer.id
            ORDER BY eintrag.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT eintrag.*, benutzer.username AS owner_name
            FROM eintrag
            LEFT JOIN benutzer ON eintrag.fk_user_id = benutzer.id
            WHERE eintrag.id = ?
        ");

        $stmt->execute([$id]);

        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        return $project ?: null;
    }

    public function createProject(string $title, ?string $description, ?string $picture, ?int $fk_user_id)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO eintrag (title, description, picture, fk_user_id)
            VALUES (?, ?, ?, ?)
        ");

        if ($stmt->execute([$title, $description, $picture, $fk_user_id])) {
            return (int)$this->pdo->lastInsertId();
        }
        return false;
    }

    public function updateProject(int $id, string $title, ?string $description, ?string $picture): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE eintrag
            SET title = ?, description = ?, picture = ?
            WHERE id = ?
        ");

        return $stmt->execute([$title, $description, $picture, $id]);
    }

    public function deleteProject(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM eintrag
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}