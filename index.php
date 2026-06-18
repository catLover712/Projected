<?php

$page = $_GET['page'] ?? 'home';

switch ($page)
{
    case 'project':
        require_once 'controllers/ProjectController.php';
        $controller = new ProjectController();
        $action = $_GET['action'] ?? 'show';
        if ($action === 'create') {
            $controller->create();
        } else {
            $controller->show();
        }
        break;

    default:
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
}