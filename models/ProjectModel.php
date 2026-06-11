<?php

class ProjectModel
{
    private array $projects = [

        [
            "id" => 1,
            "title" => "Iron Man Helmet",
            "image" => "assets/images/ironman.png"
        ],

        [
            "id" => 2,
            "title" => "Electric Guitar",
            "image" => "assets/images/guitar.jpg"
        ],

        [
            "id" => 3,
            "title" => "Leather Notebook",
            "image" => "assets/images/notebook.jpg"
        ]
    ];

    public function getAllProjects(): array
    {
        return $this->projects;
    }

    public function getProjectById(int $id): ?array
    {
        foreach ($this->projects as $project)
        {
            if ($project["id"] === $id)
            {
                return $project;
            }
        }

        return null;
    }
}