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
    public function insertTask($taskDescription, $taskPriority)
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (description, priority) VALUES (:description, :priority)");
        $stmt->bindParam(':description', $taskDescription);
        $stmt->bindParam(':priority', $taskPriority);
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
    public function updateTask($id, $newDescription, $newPriority)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET description = :description, priority = :priority, updated_at = NOW() WHERE id = :id");
        $stmt->bindParam(':description', $newDescription);
        $stmt->bindParam(':priority', $newPriority);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get all tasks
    public function getTasks($orderBy)
    {
        // $orderBy: 0 = priority, 1 = date created
        if ($orderBy == "0") {
            $stmt = $this->db->query("SELECT * FROM tasks ORDER BY priority DESC, created_at DESC");
        } else {
            $stmt = $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC, priority DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
