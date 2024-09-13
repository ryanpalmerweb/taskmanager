<?php
// classes/Task.php

class Task
{
    private $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    // Insert task into the database
    public function insertTask($taskDescription)
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (description) VALUES (:description)");
        $stmt->bindParam(':description', $taskDescription);
        return $stmt->execute();
    }

    // Delete task by ID
    public function deleteTask($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get a specific task by ID
    public function getTaskById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update task by ID
    public function updateTask($id, $newDescription)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET description = :description, updated_at = NOW() WHERE id = :id");
        $stmt->bindParam(':description', $newDescription);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get all tasks
    public function getTasks($itemsPerPage, $page)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $stmt = $this->db->prepare("SELECT * FROM tasks ORDER BY created_at DESC LIMIT :lim OFFSET :offs");
        $stmt->bindParam(':lim', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offs', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPageCount($itemsPerPage)
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tasks");
        $taskCount = $stmt->fetchColumn();
        return intdiv($taskCount, $itemsPerPage) + 1;
    }
}
