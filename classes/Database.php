<?php

class Database
{
    private $pdo;

    public function __construct()
    {
        $host = 'localhost';
        $db = 'todo_app';
        $user = 'root';
        $pass = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Could not connect to the database: " . $e->getMessage());
        }
    }

    // Method to retrieve the PDO connection
    public function getConnection()
    {
        return $this->pdo;
    }
}
