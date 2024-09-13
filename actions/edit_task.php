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

// id is passed as a GET parameter to delete_task.php, so filter it out
unset($_GET["id"]);

// if any GET parameters are set, append them to the URL
if (!empty($_GET)) {
    $queryString = http_build_query($_GET);
    header("Location: ../index.php?" . $queryString);
} else {
    // no GET parameters, redirect normally
    header("Location: ../index.php");
}
exit;
