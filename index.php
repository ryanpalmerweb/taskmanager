<?php
include 'classes/Database.php';
include 'classes/Task.php';
include 'classes/StatusHandler.php';

$database = new Database();
$taskObj = new Task($database);
$statusHandler = new StatusHandler();

$tasks = $taskObj->getTasks();
$status = $statusHandler->getStatus();

function humanReadableTimestamp($timestamp) {
    return date('F j, Y, g:i a', strtotime($timestamp));  // Example: "September 11, 2024, 10:12 am"
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
</head>
<body>
<h1>To-Do List</h1>

<!-- General Status Messages -->
<?php if ($status): ?>
    <p style="color: <?php echo ($status['type'] == 'success') ? 'green' : 'red'; ?>;">
        <?php echo htmlspecialchars($status['message']); ?>
    </p>
<?php endif; ?>

<form action="actions/add_task.php" method="POST">
    <input type="text" name="task" placeholder="New Task" required>
    <button type="submit">Add Task</button>
</form>

<ul>
    <?php foreach ($tasks as $task): ?>
        <li>
            <strong><?php echo htmlspecialchars($task['description']); ?></strong>
            <?php if (!empty($task['updated_at'])): ?>
                <em>Last updated at: <?php echo humanReadableTimestamp($task['updated_at']); ?></em>
            <?php else: ?>
                <em>Created at: <?php echo humanReadableTimestamp($task['created_at']); ?></em>
            <?php endif; ?>
            <a href="actions/delete_task.php?id=<?php echo $task['id']; ?>">Delete</a>
            <a href="edit.php?id=<?php echo $task['id']; ?>">Edit</a>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
