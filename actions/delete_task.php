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
