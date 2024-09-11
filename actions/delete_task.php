<?php
include '../classes/Database.php';
include '../classes/Task.php';
include '../classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

$id = $_GET['id'] ?? null;

if ($taskObj->deleteTask($id)) {
    $statusHandler->setStatus('success', 'Task deleted successfully.');
} else {
    $statusHandler->setStatus('error', 'Failed to delete the task.');
}

header("Location: ../index.php");  // Redirect to root index.php
exit;
