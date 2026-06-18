<?php

require_once __DIR__ . '/../models/ProjectModel.php';

class ProjectController
{
    public function show(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id === null) {
            die("Kein Projekt ausgewählt.");
        }

        $projectModel = new ProjectModel();
        $project = $projectModel->getProjectById((int)$id);

        if (!$project) {
            die("Projekt nicht gefunden.");
        }

        if (isset($_GET['delete']) && $_GET['delete'] == 1) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $project['fk_user_id']) {
                die("Berechtigung verweigert. Sie müssen der Besitzer dieses Projekts sein.");
            }

            $projectModel->deleteProject((int)$id);
            header("Location: index.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $project['fk_user_id']) {
                die("Berechtigung verweigert. Sie müssen der Besitzer dieses Projekts sein.");
            }

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? null;
            $pictureData = $project['picture'];

            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $pictureData = file_get_contents($_FILES['picture']['tmp_name']);
            }

            if (empty($title)) {
                die("Titel darf nicht leer sein.");
            }

            $projectModel->updateProject((int)$id, $title, $description, $pictureData);

            header("Location: index.php?page=project&id=" . $id);
            exit();
        }

        require __DIR__ . '/../views/project.php';
    }

    public function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /Projected/views/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? null;
            $pictureData = null;

            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $pictureData = file_get_contents($_FILES['picture']['tmp_name']);
            }

            if (empty($title)) {
                die("Titel ist ein Pflichtfeld.");
            }

            $projectModel = new ProjectModel();
            $projectId = $projectModel->createProject($title, $description, $pictureData, (int)$_SESSION['user_id']);

            if ($projectId) {
                header("Location: index.php?page=project&id=" . $projectId);
                exit();
            } else {
                die("Fehler beim Erstellen des Projekts.");
            }
        }

        require __DIR__ . '/../views/create_project.php';
    }
}