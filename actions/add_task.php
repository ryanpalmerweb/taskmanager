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

// if any GET parameters are set, append them to the URL
if (!empty($_GET)) {
    $queryString = http_build_query($_GET);
    header("Location: ../index.php?" . $queryString);
} else {
    // no GET parameters, redirect normally
    header("Location: ../index.php");
}
exit;
