<?php
include '../classes/Database.php';
include '../classes/Attachment.php';
include '../classes/StatusHandler.php';

$database = new Database();
$attachmentObj = new Attachment($database);
$statusHandler = new StatusHandler();

$taskId = $_POST['id'] ?? '';
$attachmentType = $_POST['type'] ?? '';
$attachmentBody = $_POST['body'] ?? '';

if ($attachmentObj->insertAttachment($taskId, $attachmentType, $attachmentBody)) {
    $statusHandler->setStatus('success', 'Attachment added successfully.');
} else {
    $statusHandler->setStatus('error', 'Failed to add the attachment.');
}

header("Location: ../index.php");  // Redirect to root index.php
exit;
