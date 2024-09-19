<?php
include '../classes/Database.php';
include '../classes/Attachment.php';
include '../classes/StatusHandler.php';

$database = new Database();
$attachmentObj = new Attachment($database);
$statusHandler = new StatusHandler();

$id = $_GET['id'] ?? null;

if ($attachmentObj->deleteAttachment($id)) {
    $statusHandler->setStatus('success', 'Attachment deleted successfully.');
} else {
    $statusHandler->setStatus('error', 'Failed to delete the attachment.');
}

header("Location: ../index.php");  // Redirect to root index.php
exit;