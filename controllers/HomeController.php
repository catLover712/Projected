<?php

require_once 'models/ProjectModel.php';

class HomeController
{
    public function index(): void
    {
        $projectModel = new ProjectModel();

        $projects = $projectModel->getAllProjects();

        require 'views/home.php';
    }
}