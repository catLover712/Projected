# Projected
A place to share your dear projects :) 

Projected is a PHP-based web application built using the MVC architecture for managing and displaying projects.
The application uses a MySQL database and PDO for database communication.

## Features

Display project overview
Load projects from the database
User assignment to projects
MVC architecture (Model / View / Controller)
Responsive frontend (CSS included)

## Requirements

Make sure the following software is installed:

PHP >= 8.0
MySQL / MariaDB
Apache web server (e.g. XAMPP, Laragon, or LAMP stack)
PDO MySQL extension enabled

## Installation

1. Clone the project

    Clone the repository or place it in your web server directory:

    git clone https://github.com/your-username/projected.git

    Or manually copy it into your web server folder:

    htdocs/Projected

2. Create the database

    Create a new MySQL database:

    CREATE DATABASE projected;

    Then import your database schema (if available):

    mysql -u root -p projected < database.sql

3. Configure database connection

    Open the file:

    config/database.php

    Adjust your database credentials:

    $host = 'localhost';
    $dbname = 'projected';
    $user = 'root';
    $password = '';

4. Run the project in your browser

Start your local server (e.g. Apache via XAMPP) and open:

http://localhost/Projected/

If needed:

http://localhost/Projected/index.php

## Project structure

Projected/
│
├── config/          # Database configuration
├── controllers/     # Application logic
├── models/          # Database queries (PDO)
├── views/           # HTML/PHP views
├── assets/          # CSS, JS, images
├── includes/        # Header/Footer etc.
└── index.php        # Entry point

## Developer notes

This project follows a simple MVC structure.
All database operations are handled via models using PDO.

## License

This project is intended for educational / personal use.