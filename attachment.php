<?php
include 'classes/Database.php';
include 'classes/Task.php';
include 'classes/Attachment.php';
include 'classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$attachmentObj = new Attachment($database);
$statusHandler = new StatusHandler();
$id = $_GET['id'] ?? null;

if ($id === null) {
    $statusHandler->setStatus('error', 'No task ID provided.');
    header("Location: index.php");
    exit;
}
$attachments = $attachmentObj->getAttachments($id);
$task = $taskObj->getTaskById($id);

function humanReadableTimestamp($timestamp) {
    return date('F j, Y, g:i a', strtotime($timestamp));  // Example: "September 11, 2024, 10:12 am"
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task Attachments</title>
</head>
<body>
<a href="index.php">&lt;&lt;Back</a>
<h1>View Task Attachments</h1>

<!-- Display task info -->
<p>Task id: <?php echo htmlspecialchars($task['id']); ?></p>
<p>Task message: <?php echo htmlspecialchars($task['description']); ?></p>
<p>Attachments:</p>

<ul>
    <?php foreach ($attachments as $attachment): ?>
        <li>
            <strong><?php switch ($attachment['attachment_type']) {
                case 'note':
                    echo $attachment['body'];
                    break;
                case 'url':
                    $websiteContent = file_get_contents($attachment['body']);
                    if ($websiteContent !== false) {
                        // extract <title> content with regex
                        if (preg_match('/<title>(.*?)<\/title>/i', $websiteContent, $matches)) {
                            $pageTitle = $matches[1]; // content inside <title> tag
                        } else {
                            $pageTitle = 'Click to view'; // fallback if no <title> found
                        }
                    } else {
                        $pageTitle = 'Click to view';
                    }
                    echo '<a href=' . $attachment['body'] . '>' . $pageTitle . '</a>';
                    break;
                case 'file':
                    echo '<a href=files/' . $attachment['body'] . '>' . $attachment['body'] . '</a>';
                    break;
            } ?></strong>
            <em>Created at: <?php echo humanReadableTimestamp($task['created_at']); ?></em>
            <a href="actions/delete_attachment.php?id=<?php echo $attachment['id']; ?>">Delete</a>
        </li>
    <?php endforeach; ?>
</ul>

<p>Add new attachment</p>

<!-- separate forms for each attachment type -->

<form action="actions/add_attachment.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $task['id']?>">
    <input type="hidden" name="type" value="note">
    <input type="text" name="body" placeholder="Enter text" required>
    <button type="submit">Attach a note</button>
</form>

<form action="actions/add_attachment.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $task['id']?>">
    <input type="hidden" name="type" value="url">
    <input type="text" name="body" placeholder="Enter URL" required>
    <button type="submit">Attach a URL</button>
</form>

<form enctype="multipart/form-data" action="actions/upload_file.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $task['id']?>">
    <input type="file" name="file" id="file">
    <button type="submit">Attach a file</button>
</form>

</body>