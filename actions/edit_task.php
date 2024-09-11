<?php
// actions/edit_task.php
include '../classes/Database.php';
include '../classes/Task.php';
include '../classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

$id = $_POST['id'] ?? null;
$newDescription = $_POST['task'] ?? '';

if ($id === null || empty($newDescription)) {
    $statusHandler->setStatus('error', 'Invalid task data.');
    header("Location: ../index.php");
    exit;
}

// Update the task in the database
$updated = $taskObj->updateTask($id, $newDescription);

if ($updated) {
    $statusHandler->setStatus('success', 'Task updated successfully.');
} else {
    $statusHandler->setStatus('error', 'Failed to update the task.');
}

header("Location: ../index.php");
exit;
