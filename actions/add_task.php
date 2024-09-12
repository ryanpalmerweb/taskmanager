<?php
include '../classes/Database.php';
include '../classes/Task.php';
include '../classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

$taskDescription = $_POST['task'] ?? '';
$taskPriority = $_POST['priority'] ?? '2';

if ($taskObj->insertTask($taskDescription, $taskPriority)) {
    $statusHandler->setStatus('success', 'Task added successfully.');
} else {
    $statusHandler->setStatus('error', 'Failed to add the task.');
}

header("Location: ../index.php");  // Redirect to root index.php
exit;
