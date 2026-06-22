<?php

require_once __DIR__ . '/../models/ProjectModel.php';

class ProjectController
{
    /* ---------------- SHOW PROJECT ---------------- */

    public function show(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id === null || !is_numeric($id)) {
            $this->redirectHome();
        }

        $projectModel = new ProjectModel();
        $project = $projectModel->getProjectById((int)$id);

        if (!$project) {
            $this->redirectHome();
        }

        $this->ensureSession();

        /* DELETE PROJECT */
        if (isset($_GET['delete']) && $_GET['delete'] == 1) {

            if (!$this->isOwner($project)) {
                $this->deny();
            }

            $projectModel->deleteProject((int)$id);

            header("Location: /Projected/index.php");
            exit();
        }

        /* UPDATE PROJECT */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!$this->isOwner($project)) {
                $this->deny();
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');

            /* VALIDATION (C6) */
            if (strlen($title) < 3 || strlen($title) > 45) {
                $this->redirect("index.php?page=project&id=$id&error=invalid_title");
            }

            if (strlen($description) > 300) {
                $this->redirect("index.php?page=project&id=$id&error=description_too_long");
            }

            $pictureData = $project['picture'];

            /* FILE UPLOAD VALIDATION */
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {

                if ($_FILES['picture']['size'] > 2 * 1024 * 1024) {
                    $this->redirect("index.php?page=project&id=$id&error=file_too_large");
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['picture']['tmp_name']);

                $allowed = ['image/jpeg', 'image/png', 'image/gif'];

                if (!in_array($mime, $allowed)) {
                    $this->redirect("index.php?page=project&id=$id&error=invalid_file_type");
                }

                $pictureData = file_get_contents($_FILES['picture']['tmp_name']);
            }

            $projectModel->updateProject((int)$id, $title, $description, $pictureData);

            header("Location: index.php?page=project&id=" . $id);
            exit();
        }

        require __DIR__ . '/../views/project.php';
    }

    /* ---------------- CREATE PROJECT ---------------- */

    public function create(): void
    {
        $this->ensureSession();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /Projected/views/login.php");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');

            /* VALIDATION (C6) */
            if ($title === '') {
                $this->redirect("index.php?page=project&action=create&error=missing_title");
            }

            if (strlen($title) < 3 || strlen($title) > 45) {
                $this->redirect("index.php?page=project&action=create&error=invalid_title");
            }

            if (strlen($description) > 300) {
                $this->redirect("index.php?page=project&action=create&error=description_too_long");
            }

            $pictureData = null;

            /* FILE UPLOAD VALIDATION */
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {

                if ($_FILES['picture']['size'] > 2 * 1024 * 1024) {
                    $this->redirect("index.php?page=project&action=create&error=file_too_large");
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['picture']['tmp_name']);

                $allowed = ['image/jpeg', 'image/png', 'image/gif'];

                if (!in_array($mime, $allowed)) {
                    $this->redirect("index.php?page=project&action=create&error=invalid_file_type");
                }

                $pictureData = file_get_contents($_FILES['picture']['tmp_name']);
            }

            $projectModel = new ProjectModel();

            $projectId = $projectModel->createProject(
                $title,
                $description,
                $pictureData,
                (int)$_SESSION['user_id']
            );

            if ($projectId) {
                header("Location: index.php?page=project&id=" . $projectId);
                exit();
            }

            $this->redirect("index.php?page=project&action=create&error=server");
        }

        require __DIR__ . '/../views/create_project.php';
    }

    /* ---------------- HELPERS ---------------- */

    private function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function isOwner(array $project): bool
    {
        return isset($_SESSION['user_id'])
            && $_SESSION['user_id'] == $project['fk_user_id'];
    }

    private function deny(): void
    {
        die("Berechtigung verweigert.");
    }

    private function redirect(string $url): void
    {
        header("Location: /Projected/$url");
        exit();
    }

    private function redirectHome(): void
    {
        header("Location: /Projected/index.php");
        exit();
    }
}