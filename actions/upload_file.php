<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../classes/Database.php';
include '../classes/Attachment.php';
include '../classes/StatusHandler.php';

$database = new Database();
$attachmentObj = new Attachment($database);
$statusHandler = new StatusHandler();

$taskId = $_POST['id'] ?? '';

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $statusHandler->setStatus('error', 'Failed to add the attachment.');
    header("Location: ../index.php");  // Redirect to root index.php
    exit;
}

$fileSplit = pathinfo($_FILES['file']['name']);
$fileName = $fileSplit['basename'];
$fileNameWithoutEx = $fileSplit['filename'];
$fileExtension = $fileSplit['extension'];

$fileSize = $_FILES['file']['size'];
$fileTmpName  = $_FILES['file']['tmp_name'];

// generate unique filename
if (file_exists('../files/' . $fileName)) {
    $done = false;
    $i = 1;
    while (!$done) {
        $done = !file_exists('../files/' . $fileNameWithoutEx . '_' . $i . '.' . $fileExtension);
    }
    $fileName = $fileNameWithoutEx . '_' . $i . '.' . $fileExtension;
}

$didUpload = move_uploaded_file($fileTmpName, __DIR__ . '/../files/' . $fileName);
if (!$didUpload) { 
    $statusHandler->setStatus('error', 'Failed to add the attachment.');
} else {
    if ($attachmentObj->insertAttachment($taskId, 'file', $fileName)) {
        $statusHandler->setStatus('success', 'Attachment added successfully.');
    } else {
        $statusHandler->setStatus('error', 'Failed to add the attachment.');
    }
}

header("Location: ../index.php");  // Redirect to root index.php
exit;
