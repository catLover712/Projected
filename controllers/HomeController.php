<?php

require_once __DIR__ . '/../models/ProjectModel.php';

class HomeController
{
    public function index(): void
    {
        $projectModel = new ProjectModel();

        $projects = $projectModel->getAllProjects();

        require __DIR__ . '/../views/home.php';
    }
}