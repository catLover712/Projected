<?php

require_once 'models/ProjectModel.php';

class ProjectController
{
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        $projectModel = new ProjectModel();

        $project = $projectModel->getProjectById($id);

        require 'views/project.php';
    }
}